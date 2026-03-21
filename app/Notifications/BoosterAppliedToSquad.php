<?php

namespace App\Notifications;

use App\Models\RaidEvent;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BoosterAppliedToSquad extends Notification
{
    use Queueable;

    public function __construct(public RaidEvent $raid, public User $booster, public string $notes = '') {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $message = "📡 SQUAD UPDATE: Booster {$this->booster->name} has applied to your +{$this->raid->mythic_plus_level} mission squad.";
        if ($this->notes) {
            $message .= " Message: \"{$this->notes}\"";
        }

        return [
            'type'     => 'booster_applied',
            'raid_id'  => $this->raid->id,
            'raid_title' => $this->raid->title,
            'booster_name' => $this->booster->name,
            'message'  => $message,
            'scheduled_at' => $this->raid->scheduled_at->toDateTimeString(),
        ];
    }
}
