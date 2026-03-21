<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaidSignup extends Model
{
    protected $fillable = [
        'raid_event_id', 'user_id', 'character_guid', 'character_name',
        'role', 'class', 'spec', 'status', 'notes',
        'advertiser_user_id', 'buyer_realm', 'buyer_faction', 'agreed_price',
        'armor_type', 'loot_priority', 'payment_realm', 'deposit_amount', 'ad_cut', 'client_discord',
        'advertiser_commission_percentage', 'is_paid', 'paid_at', 'collector_user_id', 'payment_method', 'is_booster', 'group_invite_code',
        'attendance_status', 'payout_amount', 'client_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collector_user_id');
    }

    protected $casts = [
        'agreed_price' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'ad_cut' => 'decimal:2',
        'is_paid' => 'boolean',
        'is_booster' => 'boolean',
        'paid_at' => 'datetime',
        'payout_amount' => 'decimal:2',
    ];

    public function advertiser()
    {
        return $this->belongsTo(User::class, 'advertiser_user_id');
    }

    public function event()
    {
        return $this->belongsTo(RaidEvent::class, 'raid_event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
