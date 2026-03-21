<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaidAttendance extends Model
{
    protected $fillable = [
        'raid_event_id', 'character_guid', 'joined_at', 'left_at', 'present'
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(RaidEvent::class, 'raid_event_id');
    }
}
