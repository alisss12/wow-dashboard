<?php

namespace App\Notifications;

use App\Models\RaidEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MissionCancelled extends Notification
{
    use Queueable;

    public function __construct(public RaidEvent $raid, public string $reason = '') {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $msg = "❌ MISSION CANCELLED: The operation for {$this->raid->title} has been aborted.";
        if ($this->reason) {
            $msg .= " Reason: {$this->reason}";
        }

        return [
            'type'     => 'mission_cancelled',
            'raid_id'  => $this->raid->id,
            'raid_title' => $this->raid->title,
            'message'  => $msg,
            'scheduled_at' => $this->raid->scheduled_at->toDateTimeString(),
        ];
    }
}
