<?php

namespace App\Notifications;

use App\Models\RaidEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentConfirmed extends Notification
{
    use Queueable;

    public function __construct(public RaidEvent $raid, public string $characterName) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'     => 'payment_confirmed',
            'raid_id'  => $this->raid->id,
            'raid_title' => $this->raid->title,
            'message'  => "💰 PAYMENT VERIFIED: Payment for {$this->characterName} has been confirmed for mission {$this->raid->title}.",
            'scheduled_at' => $this->raid->scheduled_at->toDateTimeString(),
        ];
    }
}
