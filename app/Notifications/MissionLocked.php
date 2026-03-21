<?php

namespace App\Notifications;

use App\Models\RaidEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MissionLocked extends Notification
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
            'type'     => 'mission_locked',
            'raid_id'  => $this->raid->id,
            'raid_title' => $this->raid->title,
            'message'  => "🔒 ROSTER LOCKED: The squad for {$this->raid->title} has been finalized. No further changes allowed. Ready for deployment.",
            'scheduled_at' => $this->raid->scheduled_at->toDateTimeString(),
        ];
    }
}
