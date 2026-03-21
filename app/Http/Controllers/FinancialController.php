<?php

namespace App\Http\Controllers;

use App\Models\PaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialController extends Controller
{
    public function requestPayout(Request $request)
    {
        $user = Auth::user();
        
        // DawnHub Threshold: 200,000 Gold
        if ($user->balance < 200000) {
            return back()->with('error', 'Minimum balance of 200,000 Gold required for payout.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $user->balance,
            'character_name' => 'required|string|max:100',
            'realm' => 'required|string|max:100',
        ]);

        PaymentRequest::create([
            'user_id' => $user->id,
            'type' => 'withdrawal',
            'amount' => $request->amount,
            'status' => 'pending',
            'gateway' => 'In-Game Mail',
            'payout_character' => $request->character_name,
            'payout_realm' => $request->realm,
        ]);

        return back()->with('status', 'Payout request submitted. Gold will be mailed in-game once approved.');
    }
}
