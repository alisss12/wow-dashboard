<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WowAccount extends Model
{
    // Define the external database connection representing the Emulator's 'auth' DB
    protected $connection = 'auth';
    
    // The table name in TrinityCore/AzerothCore is usually 'account'
    protected $table = 'account';

    public $timestamps = false; // Emulators usually don't track updated_at natively

    protected $fillable = [
        'username', 'email', 'salt', 'verifier', 'sha_pass_hash', 'expansion', 'session_key', 'v', 's'
    ];
}
