<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MplusSquad extends Model
{
    use HasFactory;

    protected $table = 'active_mplus_squads';
    protected $primaryKey = 'advertiser_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'advertiser_id',
        'squad_json',
        'created_at'
    ];

    public function getSquadDataAttribute()
    {
        return json_decode($this->squad_json, true) ?: ['verboseDetails' => [], 'members' => []];
    }
}
