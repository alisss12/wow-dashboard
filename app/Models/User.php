<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->account_type, ['admin', 'staff']);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'account_type',
        'balance',
        'discord_id',
        'discord_name',
        'avatar',
        'oauth_token',
        'oauth_refresh_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationships for DawnHub Ecosystem
     */
    
    public function signups()
    {
        return $this->hasMany(RaidSignup::class);
    }

    public function advertisedSignups()
    {
        return $this->hasMany(RaidSignup::class, 'advertiser_user_id');
    }

    public function hostedRaids()
    {
        return $this->hasMany(RaidEvent::class, 'booster_user_id');
    }

    public function transactions()
    {
        return $this->hasMany(LedgerTransaction::class);
    }

    public function balance()
    {
        return (float) $this->transactions()->sum('amount');
    }

    public function isBooster(): bool
    {
        return in_array($this->account_type, ['booster', 'staff', 'admin']);
    }

    public function isAdvertiser(): bool
    {
        return $this->account_type === 'advertiser';
    }
}
