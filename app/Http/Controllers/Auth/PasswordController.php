<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        if ($user->game_account_id) {
            try {
                $wowService = new \App\Services\WowAuthService();
                $wowService->updatePassword($user->game_account_id, $user->name, $validated['password']);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to sync password to game server for user {$user->id}: " . $e->getMessage());
                // Optionally add a session flash message here to warn them that the game server is unreachable
            }
        }

        return back()->with('status', 'password-updated');
    }
}
