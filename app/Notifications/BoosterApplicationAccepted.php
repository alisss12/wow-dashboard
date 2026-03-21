<?php

namespace App\Notifications;

use App\Models\RaidEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BoosterApplicationAccepted extends Notification
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
            'type'     => 'accepted',
            'raid_id'  => $this->raid->id,
            'raid_title' => $this->raid->title,
            'message'  => "✅ Your deployment request for \"{$this->raid->title}\" has been AUTHORIZED. Report for duty.",
            'scheduled_at' => $this->raid->scheduled_at->toDateTimeString(),
        ];
    }
}
