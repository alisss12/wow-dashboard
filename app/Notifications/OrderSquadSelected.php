<?php

namespace App\Notifications;

use App\Models\RaidEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderSquadSelected extends Notification
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
            'type'     => 'squad_selected',
            'raid_id'  => $this->raid->id,
            'raid_title' => $this->raid->title,
            'message'  => "⚔️ DEPLOYMENT AUTHORIZED: An elite squad has been assigned to your +{$this->raid->mythic_plus_level} mission. Tracking is now active in your 'My Requests' dashboard.",
            'scheduled_at' => $this->raid->scheduled_at->toDateTimeString(),
        ];
    }
}
