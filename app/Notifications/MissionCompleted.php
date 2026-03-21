<?php

namespace App\Notifications;

use App\Models\RaidEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MissionCompleted extends Notification
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
            'type'     => 'mission_completed',
            'raid_id'  => $this->raid->id,
            'raid_title' => $this->raid->title,
            'message'  => "✅ MISSION COMPLETE: The operation for {$this->raid->title} has been finalized. Payouts have been distributed to the squad.",
            'scheduled_at' => $this->raid->scheduled_at->toDateTimeString(),
        ];
    }
}
