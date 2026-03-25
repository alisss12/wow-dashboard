<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\RaidEvent;
use App\Models\RaidSignup;
use App\Models\LedgerTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BoosterDashboard extends Component
{
    public $sharedData = [];
    #[Url(as: 'tab')]
    public $activeTab = 'available';
    public $showSubmitModal = false;
    public $showCreateModal = false;
    
    // Form fields for new operation
    public $raid_type, $difficulty, $scheduled_at, $group_type, $loot_type;
    public $pot_size;
    
    // Mission Intel fields
    public $submissionRaidId, $goldPot, $submissionProof;

    protected $listeners = ['refreshDashboard' => '$refresh'];

    public function mount($sharedData = [])
    {
        $this->sharedData = is_array($sharedData) ? $sharedData : [];
        if (empty($this->sharedData)) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Dashboard data unavailable. Please refresh.']);
        }
    }

    public function takeSpot($raidId, $role = 'DPS')
    {
        $raid = RaidEvent::findOrFail($raidId);
        $exists = RaidSignup::where('raid_event_id', $raidId)->where('user_id', Auth::id())->exists();

        if ($exists) {
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Slot Taken', 'message' => 'Already registered for this run.']);
            return;
        }

        RaidSignup::create([
            'raid_event_id' => $raidId,
            'user_id' => Auth::id(),
            'role' => $role,
            'status' => 'accepted', 
            'is_booster' => true,
            'class' => 'Booster',
            'character_name' => Auth::user()->name,
            'attendance_status' => 'present'
        ]);

        // Move to locked/active so advertisers see their run was picked up by a booster
        $raid->update([
            'status' => 'locked',
            'booster_user_id' => Auth::id(),
            'leader_user_id' => Auth::id(),
            'assigned_leader_id' => Auth::id()
        ]);

        $this->dispatch('notify', ['type' => 'success', 'message' => "Spot successfully secured."]);
        $this->dispatch('refreshDashboard');
        $this->dispatch('refreshBalance');
    }

    public function createNewRaid()
    {
        $this->validate([
            'raid_type' => 'required',
            'difficulty' => 'required',
            'scheduled_at' => 'required|date',
            'group_type' => 'required',
            'loot_type' => 'required',
            'pot_size' => 'required|numeric|min:0',
        ]);

        RaidEvent::create([
            'title' => $this->raid_type . ' ' . $this->difficulty,
            'raid_type' => $this->raid_type,
            'difficulty' => $this->difficulty,
            'scheduled_at' => $this->scheduled_at,
            'group_type' => $this->group_type,
            'loot_type' => $this->loot_type,
            'pot_size' => $this->pot_size,
            'price_per_spot' => $this->pot_size,
            'booster_user_id' => Auth::id(), 
            'leader_user_id' => Auth::id(),
            'status' => RaidEvent::STATUS_APPROVED,
            'created_by' => Auth::id(),
        ]);

        $this->reset(['raid_type', 'difficulty', 'scheduled_at', 'group_type', 'loot_type', 'pot_size', 'showCreateModal']);
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Raid created successfully.']);
        $this->dispatch('refreshDashboard');
    }

    public function approveSignup($signupId)
    {
        $signup = RaidSignup::findOrFail($signupId);
        $signup->update(['status' => 'accepted']);

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Signup approved.']);
        $this->dispatch('refreshDashboard');
    }

    /**
     * Real Dawn Gold Distribution Logic
     * Splits: Booster Pool 75% | House 15% | Advertiser Cut (approx 10%)
     */
    public function submitCompletion()
    {
        if (!$this->submissionRaidId || !$this->goldPot) return;

        $raid = RaidEvent::findOrFail($this->submissionRaidId);
        $raid->update(['status' => RaidEvent::STATUS_COMPLETED]);

        $totalPot = (float)$this->goldPot;
        $boosters = $raid->signups()->where('is_booster', true)->where('attendance_status', 'present')->get();
        $advertisers = $raid->signups()->where('is_booster', false)->whereNotNull('advertiser_user_id')->get();

        $mailStringRows = [];
        $adTotal = 0;

        if ($boosters->count() > 0) {
            // 1. Advertiser Cut (15% per requirements)
            foreach ($advertisers as $adSignup) {
                $cut = $adSignup->agreed_price * 0.15;
                $adTotal += $cut;
                
                $adName = $adSignup->advertiser ? $adSignup->advertiser->name : 'Unknown';
                $mailStringRows[] = "Send {$cut}g to Advertiser ({$adName})";

                LedgerTransaction::create([
                    'user_id' => $adSignup->advertiser_user_id,
                    'amount' => $cut,
                    'type' => 'advertiser_commission',
                    'description' => "Commission for {$raid->title}",
                    'reference_id' => $raid->id,
                    'reference_type' => RaidEvent::class
                ]);
            }

            // 2. House Cut (10%)
            $houseCut = $totalPot * 0.10;
            $mailStringRows[] = "Send {$houseCut}g to Admin-TarrenMill (House Cut)";
            $admin = User::where('account_type', 'admin')->first();
            if ($admin) {
                LedgerTransaction::create([
                    'user_id' => $admin->id,
                    'amount' => $houseCut,
                    'type' => 'house_cut',
                    'description' => "Management fee: {$raid->title}",
                    'reference_id' => $raid->id,
                    'reference_type' => RaidEvent::class
                ]);
            }

            // 3. Booster Split (75% or remaining)
            $boosterPool = max(0, $totalPot - $houseCut - $adTotal);
            if ($boosterPool > 0) {
                $perBooster = $boosterPool / max(1, $boosters->count());
                foreach ($boosters as $booster) {
                    $bName = $booster->character_name ?? 'Booster';
                    $mailStringRows[] = "Send {$perBooster}g to {$bName}";

                    LedgerTransaction::create([
                        'user_id' => $booster->user_id,
                        'amount' => $perBooster,
                        'type' => 'booster_payout',
                        'description' => "Raid payout: {$raid->title}",
                        'reference_id' => $raid->id,
                        'reference_type' => RaidEvent::class
                    ]);
                    $booster->update(['payout_amount' => $perBooster, 'is_paid' => true, 'paid_at' => now()]);
                }
            }
        }

        $finalMailString = implode(" | ", $mailStringRows);
        $this->dispatch('copy-to-clipboard', content: $finalMailString);
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Raid completed. Mail string copied to clipboard!']);
        
        $this->reset(['showSubmitModal', 'submissionRaidId', 'goldPot', 'submissionProof']);
        $this->dispatch('refreshDashboard');
        $this->dispatch('refreshBalance');
    }

    public function approveRaid($raidId)
    {
        $raid = RaidEvent::findOrFail($raidId);
        $raid->update(['status' => RaidEvent::STATUS_APPROVED]);
        
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Raid approved and added to list.']);
        $this->dispatch('refreshDashboard');
    }

    public function render()
    {
        return view('livewire.booster-dashboard', [
            'availableRaids' => collect($this->sharedData['availableRaids'] ?? [])->map(fn($r)=>(object)$r),
            'myBoosts' => collect($this->sharedData['mySignups'] ?? [])->map(function($s){
                $o = (object)$s;
                if(isset($o->event)) {
                    $o->event = (object)$o->event;
                    if(isset($o->event->scheduled_at)) $o->event->scheduled_at = Carbon::parse($o->event->scheduled_at);
                }
                return $o;
            }),
            'ledRaids' => collect($this->sharedData['hostedRaids'] ?? [])->map(function($r){
                $o = (object)$r;
                $o->signups = collect($o->signups ?? [])->map(fn($s)=>(object)$s);
                return $o;
            }),
            'pendingSignups' => collect($this->sharedData['incomingApplications'] ?? [])->map(fn($s)=>(object)$s),
            'pendingRaids' => collect($this->sharedData['pendingRaids'] ?? [])->map(fn($r)=>(object)$r),
            'stats' => array_merge([
                'active_runs' => 0,
                'total_earnings' => 0,
                'completion_rate' => 0,
            ], $this->sharedData['stats'] ?? [])
        ]);
    }
}
