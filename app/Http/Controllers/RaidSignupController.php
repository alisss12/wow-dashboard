<?php

namespace App\Http\Controllers;

use App\Models\RaidSignup;
use Illuminate\Http\Request;

class RaidSignupController extends Controller
{
    public function updateStatus(Request $request, RaidSignup $signup)
    {
        $request->validate([
            'status' => 'required|in:accepted,waitlist,rejected,pending'
        ]);

        $signup->update(['status' => $request->status]);

        return back()->with('success', 'Signup status updated protocol successful.');
    }

    public function edit(RaidSignup $signup)
    {
        // Only owner, admin, or staff
        if (auth()->id() !== $signup->advertiser_user_id && !in_array(auth()->user()->account_type, ['admin', 'staff'])) {
            abort(403);
        }

        // Cannot edit if run is already active or completed
        if (in_array($signup->event->status, ['running', 'completed', 'cancelled'])) {
            return redirect()->back()->with('error', 'Cannot edit order for a mission that is already running, completed, or cancelled.');
        }

        return view('raids.signups.edit', compact('signup'));
    }

    public function update(Request $request, RaidSignup $signup)
    {
        if (auth()->id() !== $signup->advertiser_user_id && !in_array(auth()->user()->account_type, ['admin', 'staff'])) {
            abort(403);
        }

        if (in_array($signup->event->status, ['running', 'completed', 'cancelled'])) {
            return redirect()->back()->with('error', 'Cannot modify order for a mission in this state.');
        }

        // 30-minute cutoff rule (Admins/Staff bypass)
        if (!in_array(auth()->user()->account_type, ['admin', 'staff']) && now()->greaterThanOrEqualTo($signup->event->scheduled_at->copy()->subMinutes(30))) {
            return redirect()->back()->with('error', 'Protocol Lock: Orders cannot be modified within 30 minutes of deployment.');
        }

        $request->validate([
            'character_name' => 'required',
            'role' => 'required',
            'class' => 'required',
            'spec' => 'nullable',
            'buyer_realm' => 'nullable|string',
            'buyer_faction' => 'nullable|in:alliance,horde',
            'armor_type' => 'nullable|string',
            'loot_priority' => 'nullable|string',
            'client_discord' => 'nullable|string',
            'agreed_price' => 'required|numeric|min:0',
            'notes' => 'nullable|max:500',
        ]);

        $signup->update($request->only([
            'character_name', 'role', 'class', 'spec', 'buyer_realm', 'buyer_faction', 
            'armor_type', 'loot_priority', 'client_discord', 'agreed_price', 'notes'
        ]));

        // Recalculate ad_cut if price changed
        $signup->update(['ad_cut' => $request->agreed_price * 0.10]);

        return redirect()->route('dashboard')->with('success', 'Order updated successfully.');
    }

    public function destroy(RaidSignup $signup)
    {
        if (auth()->id() !== $signup->advertiser_user_id && !in_array(auth()->user()->account_type, ['admin', 'staff'])) {
            abort(403);
        }

        if (in_array($signup->event->status, ['running', 'completed'])) {
            return redirect()->back()->with('error', 'Cannot cancel order for a mission that is already running or completed.');
        }

        // 30-minute cutoff rule (Admins/Staff bypass)
        if (!in_array(auth()->user()->account_type, ['admin', 'staff']) && now()->greaterThanOrEqualTo($signup->event->scheduled_at->copy()->subMinutes(30))) {
            return redirect()->back()->with('error', 'Protocol Lock: Orders cannot be cancelled within 30 minutes of deployment.');
        }

        $event = $signup->event;
        $signup->delete();

        // If this was an M+ queue request created by this advertiser, cancel the event too
        if ($event && $event->is_queue && $event->created_by === auth()->id()) {
            $event->update(['status' => 'cancelled']);
            // Also cancel any other signups (boosters) for this M+ event
            $event->signups()->update(['status' => 'cancelled']);
        }

        return redirect()->route('dashboard')->with('success', 'Order cancelled successfully.');
    }

    public function confirmPayment(Request $request, RaidSignup $signup)
    {
        // Only collectors, admins, OR the advertiser who owns the order
        $isOwner = (auth()->id() === $signup->advertiser_user_id);
        if (!in_array(auth()->user()->account_type, ['collector', 'admin']) && !$isOwner) {
            abort(403);
        }

        $signup->update([
            'is_paid' => true,
            'paid_at' => now(),
            'collector_user_id' => auth()->id(),
            'payment_method' => $request->payment_method ?? 'Trade'
        ]);

        // Notify squad and admins
        $raid = $signup->event;
        $squad = $raid->signups()->where('is_booster', true)->where('status', 'accepted')->get();
        foreach ($squad as $boosterSignup) {
            if ($boosterSignup->user) {
                $boosterSignup->user->notify(new \App\Notifications\PaymentConfirmed($raid, $signup->character_name));
            }
        }

        $admins = \App\Models\User::whereIn('account_type', ['admin', 'staff'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\PaymentConfirmed($raid, $signup->character_name));
        }

        return back()->with('success', "Payment for {$signup->character_name} verified. Mission status: PAID.");
    }
}
