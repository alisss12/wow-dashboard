<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaidSoftReserve extends Model
{
    protected $fillable = [
        'raid_event_id', 'character_guid', 'item_id', 'priority'
    ];

    public function event()
    {
        return $this->belongsTo(RaidEvent::class, 'raid_event_id');
    }
}
