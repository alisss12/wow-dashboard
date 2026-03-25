<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\RaidEvent;
use App\Models\RaidSignup;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdvertiserDashboard extends Component
{
    public $sharedData = [];
    
    #[Url(as: 'tab')]
    public $activeTab = 'overview';
    public $showPostModal = false;
    public $raid_type, $difficulty, $scheduled_at, $group_type, $loot_type;
    public $pot_size, $deposit, $owes, $buyer_name, $buyer_realm, $character_class;
    public $payment_realm, $public_note, $private_note, $paid_full = false;

    protected $listeners = ['refreshDashboard' => '$refresh'];

    public function mount($sharedData = [])
    {
        $this->sharedData = is_array($sharedData) ? $sharedData : [];
    }

    public $selectedRaidId = null;

    public function openAddBuyerModal($raidId)
    {
        $this->selectedRaidId = $raidId;
        $this->showPostModal = true;
    }

    public function addBuyer()
    {
        $this->validate([
            'deposit' => 'required|numeric|min:0',
            'owes' => 'required|numeric|min:0',
            'buyer_name' => 'required',
            'buyer_realm' => 'required',
            'character_class' => 'required',
            'payment_realm' => 'required',
        ]);

        if (!$this->selectedRaidId) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'No raid selected.']);
            return;
        }

        RaidSignup::create([
            'raid_event_id' => $this->selectedRaidId,
            'advertiser_user_id' => Auth::id(),
            'character_name' => $this->buyer_name,
            'buyer_realm' => $this->buyer_realm,
            'class' => $this->character_class,
            'payment_realm' => $this->payment_realm,
            'deposit_amount' => $this->deposit,
            'agreed_price' => $this->deposit + $this->owes,
            'notes' => 'Public: ' . $this->public_note . ' | Private: ' . $this->private_note,
            'is_paid' => $this->paid_full ? true : false,
            'status' => 'accepted',
            'is_booster' => false,
            'attendance_status' => 'present'
        ]);

        $this->reset([
            'deposit', 'owes', 'buyer_name', 'buyer_realm', 'character_class',
            'payment_realm', 'public_note', 'private_note', 'paid_full', 'showPostModal', 'selectedRaidId'
        ]);
        
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Buyer secured for raid!']);
        $this->dispatch('refreshDashboard');
    }

    public function generateMailString($signupId = null)
    {
        if (!$signupId) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'No booking selected.']);
            return;
        }

        $signup = RaidSignup::with('event')->find($signupId);
        if (!$signup) return;

        $target = $signup->character_name ?? 'Target';
        $realm = $signup->buyer_realm ?? 'Realm';
        $gold = number_format($signup->agreed_price, 0, '', '');
        
        $mailString = "To: {$target}-{$realm} | Amount: {$gold}g | Raid: {$signup->event->title}";

        $this->dispatch('copy-to-clipboard', content: $mailString);
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Mail string copied to clipboard.']);
    }

    public function render()
    {
        return view('livewire.advertiser-dashboard', [
            'activeBookings' => collect($this->sharedData['mySignups'] ?? [])->map(function($s) {
                $o = (object)$s;
                if(isset($o->event)){
                    $o->event = (object)$o->event;
                    if(isset($o->event->scheduled_at)) $o->event->scheduled_at = Carbon::parse($o->event->scheduled_at);
                }
                return $o;
            }),
            'availableRaids' => collect($this->sharedData['availableRaids'] ?? [])->map(fn($r) => (object)$r),
            'stats' => array_merge([
                'total_sales' => 0,
                'active_bookings' => 0,
                'commission_earned' => 0,
            ], $this->sharedData['stats'] ?? [])
        ]);
    }
}
