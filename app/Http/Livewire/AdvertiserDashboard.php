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
    public $title, $instance, $difficulty, $scheduled_at, $price;

    protected $listeners = ['refreshDashboard' => '$refresh'];

    public function mount($sharedData = [])
    {
        $this->sharedData = is_array($sharedData) ? $sharedData : [];
    }

    public function postNewRequest()
    {
        $this->validate([
            'title' => 'required|min:5',
            'instance' => 'required',
            'difficulty' => 'required',
            'scheduled_at' => 'required|date|after:now',
            'price' => 'required|numeric|min:0',
        ]);

        RaidEvent::create([
            'title' => $this->title,
            'instance_name' => $this->instance,
            'difficulty' => $this->difficulty,
            'scheduled_at' => $this->scheduled_at,
            'price_per_spot' => $this->price,
            'requested_by_user_id' => Auth::id(),
            'status' => RaidEvent::STATUS_PENDING,
        ]);

        $this->reset(['title', 'instance', 'difficulty', 'scheduled_at', 'price', 'showPostModal']);
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Booking request posted successfully.']);
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
