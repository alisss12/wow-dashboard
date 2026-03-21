<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RunNote extends Model
{
    protected $fillable = [
        'raid_event_id', 'user_id', 'type', 'content'
    ];

    public function raidEvent()
    {
        return $this->belongsTo(RaidEvent::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }}
