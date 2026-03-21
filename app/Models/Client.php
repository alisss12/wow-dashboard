<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'character_name',
        'realm',
        'region',
        'type',
        'orders_count',
        'total_spent',
        'flag_reason',
        'discord_id',
    ];

    public function signups()
    {
        return $this->hasMany(RaidSignup::class);
    }
}
