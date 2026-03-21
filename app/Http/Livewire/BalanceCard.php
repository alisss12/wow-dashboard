<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class BalanceCard extends Component
{
    public $balance = 0;

    protected $listeners = ['refreshBalance' => 'mount'];

    public function mount()
    {
        $user = Auth::user();
        if ($user) {
            $this->balance = $user->balance();
        }
    }

    public function copyMailString()
    {
        // Dispatch event to the browser listener in dashboard-layout
        $this->dispatch('copy-to-clipboard', content: (string) $this->balance);

        // Optional: Show a small toast notification via dispatch if a global listener exists
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'Data Transmission',
            'message' => 'Payout string copied to clipboard!'
        ]);
    }

    public function render()
    {
        return view('livewire.balance-card');
    }
}
