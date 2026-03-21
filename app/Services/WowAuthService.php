<?php

namespace App\Services;

use App\Models\WowAccount;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class WowAuthService
{
    /**
     * Determine the hashing method (srp6 for modern AC/TC, sha1 for older cores)
     */
    protected $hashMethod = 'srp6'; // Change to 'sha1' if using an older emulator

    /**
     * Create a new WoW Emulator Account
     */
    public function createAccount(string $username, string $password, string $email): ?int
    {
        $username = strtoupper($username);
        
        // 1. Check if user exists in auth.account
        $exists = WowAccount::where('username', $username)->exists();
        if ($exists) {
            return null; // Username taken
        }

        // 2. Generate Auth String based on Core
        if ($this->hashMethod === 'srp6') {
            [$salt, $verifier] = $this->calculateSRP6($username, $password);
            
            $account = WowAccount::create([
                'username' => $username,
                'email' => $email,
                'salt' => $salt,
                'verifier' => $verifier,
                'expansion' => 2, // WotLK as default
            ]);
        } else {
            // Fallback for older SHA1 cores (Mangos/Old Trinity)
            $shaPassHash = strtoupper(sha1(strtoupper($username) . ':' . strtoupper($password)));
            
            $account = WowAccount::create([
                'username' => $username,
                'email' => $email,
                'sha_pass_hash' => $shaPassHash,
                'expansion' => 2,
            ]);
        }

        return $account->id;
    }

    /**
     * Update an existing WoW Emulator Account Password
     */
    public function updatePassword(int $accountId, string $username, string $newPassword): bool
    {
        $account = WowAccount::find($accountId);
        if (!$account) return false;

        $username = strtoupper($username);

        if ($this->hashMethod === 'srp6') {
            [$salt, $verifier] = $this->calculateSRP6($username, $newPassword);
            $account->salt = $salt;
            $account->verifier = $verifier;
            // Clear session key so they are forced to re-login with new password
            $account->session_key = ''; 
            $account->v = ''; 
            $account->s = ''; 
        } else {
            $shaPassHash = strtoupper(sha1(strtoupper($username) . ':' . strtoupper($newPassword)));
            $account->sha_pass_hash = $shaPassHash;
            $account->session_key = ''; 
            $account->v = ''; 
            $account->s = ''; 
        }

        return $account->save();
    }

    /**
     * Calculate SRP6 Salt and Verifier (AzerothCore / TrinityCore 3.3.5a+)
     */
    private function calculateSRP6(string $username, string $password): array
    {
        // Convert to uppercase as required by WoW protocols
        $username = strtoupper($username);
        $password = strtoupper($password);

        // Generate 32 bytes of random salt
        $salt = random_bytes(32);

        // Calculate identity hash: H(I || ':' || P)
        $identityHash = sha1($username . ':' . $password, true);

        // Calculate x = H(s || H(I || ':' || P))
        $x = sha1($salt . $identityHash, true);
        
        // Reverse x to match WoW's little-endian multi-precision integer format
        $x = strrev($x);

        // Generator (g) for WoW is usually 7
        $g = gmp_init(7);

        // The safe prime (N) used by WoW
        $N = gmp_init('894B645E89E1535BBDAD5B8B290650530801B18EBFBF5E8FAB3C82872A3E9BB7', 16);

        // Calculate v = g^x mod N
        $xInt = gmp_import($x);
        $v = gmp_powm($g, $xInt, $N);

        // Export v back into binary string
        $vBin = gmp_export($v);

        // Pad to 32 bytes and reverse back to little-endian for the database
        $vBin = str_pad($vBin, 32, chr(0), STR_PAD_LEFT);
        $vBin = strrev($vBin);

        return [$salt, $vBin];
    }
}
