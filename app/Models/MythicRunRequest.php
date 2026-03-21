<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MythicRunRequest extends Model
{
    const STATUS_WAITING = 'waiting';
    const STATUS_GROUPING = 'grouping';
    const STATUS_RUNNING = 'running';
    const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'service_id', 'client_user_id', 'status', 'price'
    ];

    public function service()
    {
        return $this->belongsTo(BoostingService::class, 'service_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_user_id');
    }}
