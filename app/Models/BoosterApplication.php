<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoosterApplication extends Model
{
    protected $fillable = [
        'user_id', 'character_name', 'realm', 'class', 'spec', 
        'roles', 'experience', 'logs_url', 'status', 'staff_notes'
    ];

    protected $casts = [
        'roles' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
