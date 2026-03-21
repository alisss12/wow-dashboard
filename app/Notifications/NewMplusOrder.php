<?php

namespace App\Notifications;

use App\Models\RaidEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMplusOrder extends Notification
{
    use Queueable;

    public function __construct(public RaidEvent $raid) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'     => 'new_mplus_order',
            'raid_id'  => $this->raid->id,
            'raid_title' => $this->raid->title,
            'message'  => "📡 NEW M+ ORDER: A +{$this->raid->mythic_plus_level} request has been placed in the queue. Monitoring required.",
            'scheduled_at' => $this->raid->scheduled_at->toDateTimeString(),
        ];
    }
}
