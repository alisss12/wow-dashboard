<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerTransaction extends Model
{
    protected $fillable = [
        'user_id', 'amount', 'type', 'description', 'reference_id', 'reference_type'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
