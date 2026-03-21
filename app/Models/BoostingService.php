<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoostingService extends Model
{
    protected $fillable = [
        'name', 'category', 'base_price', 'required_boosters', 
        'max_clients', 'required_tanks', 'required_healers', 
        'required_dps', 'is_active'
    ];

    protected $casts = [
        'base_price' => 'decimal:0',
        'is_active' => 'boolean',
    ];

    public function raidEvents()
    {
        return $this->hasMany(RaidEvent::class, 'service_id');
    }
}
