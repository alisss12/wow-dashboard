<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    protected $fillable = [
        'user_id',
        'type', // 'deposit' or 'withdrawal'
        'amount',
        'status', // 'pending', 'approved', 'declined'
        'gateway', // e.g., 'paypal', 'crypto', 'bank'
        'details', // e.g., transaction ID, crypto address, info
        'admin_notes',
        'payout_character',
        'payout_realm',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
