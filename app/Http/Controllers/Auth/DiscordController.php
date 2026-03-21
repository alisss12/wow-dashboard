<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class DiscordController extends Controller
{
    public function redirectToDiscord()
    {
        return Socialite::driver('discord')->redirect();
    }

    public function handleDiscordCallback()
    {
        try {
            $discordUser = Socialite::driver('discord')->user();
            
            $user = User::updateOrCreate([
                'discord_id' => $discordUser->id,
            ], [
                'name' => $discordUser->getName() ?? $discordUser->getNickname(),
                'email' => $discordUser->getEmail(),
                'discord_name' => $discordUser->getNickname() ?? $discordUser->getName(),
                'avatar' => $discordUser->getAvatar(),
                'oauth_token' => $discordUser->token,
                'oauth_refresh_token' => $discordUser->refreshToken,
                // Default account type is user, will be promoted to booster/admin manually
                'account_type' => 'user', 
                'password' => bcrypt(Str::random(24)),
            ]);

            Auth::login($user);

            // Role-based redirection for better UX
            return match ($user->account_type) {
                'admin', 'staff' => redirect('/admin'), // Send management/admin to Filament
                'booster', 'advertiser' => redirect('/dashboard'), // Send operatives to their specific dashboards
                default => redirect('/dashboard'),
            };
        } catch (\Exception $e) {
            \Log::error('Discord OAuth Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Authentication failed.');
        }
    }
}
