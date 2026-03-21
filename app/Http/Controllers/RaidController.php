<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RaidEvent;
use App\Models\RaidSignup;

class RaidController extends Controller
{
    public function index()
    {
        // Show upcoming raids
        $raids = RaidEvent::where('scheduled_at', '>=', now())
            ->whereIn('status', ['approved', 'open', 'locked', 'full'])
            ->orderBy('scheduled_at', 'asc')
            ->get();
            
        return view('raids.index', compact('raids'));
    }

    public function show(RaidEvent $raid)
    {
        // If the user is the Raid Leader or Admin, show the Management View
        if (auth()->id() === $raid->leader_user_id || (auth()->check() && auth()->user()->account_type === 'admin')) {
            $allBoosters = \App\Models\User::where('account_type', 'booster')->orderBy('name')->get();
            return view('raid-leader.management', compact('raid', 'allBoosters'));
        }

        // Show raid details and roster for clients/advertisers
        $signups = $raid->signups()->with(['user', 'client'])->get();
        // Group roster by role
        $roster = $signups->groupBy('role');
        
        $userSignup = null; // Advertisers can book multiple clients, so we don't block the form

        return view('raids.show', compact('raid', 'roster', 'userSignup', 'signups'));
    }

    public function signup(Request $request, RaidEvent $raid)
    {
        $user = auth()->user();

        // Access Control: Only Advertisers, Staff or Admins can book clients into runs
        if (!in_array($user->account_type, ['advertiser', 'admin', 'staff'])) {
            return redirect()->back()->with('error', 'Only verified Advertisers or Staff can book clients into raids.');
        }

        $request->validate([
            'character_name' => 'required',
            'role' => 'required',
            'class' => 'required',
            'spec' => 'nullable',
            'buyer_realm' => 'nullable|string|max:255',
            'buyer_faction' => 'nullable|in:alliance,horde',
            'agreed_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|max:500',
            'armor_type' => 'nullable|string|in:Plate,Mail,Leather,Cloth,N/A',
            'loot_priority' => 'nullable|string|max:255',
            'payment_realm' => 'nullable|string|max:255',
            'deposit_amount' => 'nullable|numeric|min:0',
            'client_discord' => 'nullable|string|max:100',
        ]);

        if (!in_array($raid->status, ['open', 'approved'])) {
            return redirect()->back()->with('error', 'Signups are closed for this raid. Current status: ' . $raid->status);
        }

        $agreedPrice = $request->input('agreed_price', 0);
        $adCut = $agreedPrice * 0.10; // 10% Advertiser Commission by default

        // Client Reputation Logic
        $client = \App\Models\Client::firstOrCreate([
            'character_name' => $request->input('character_name'),
            'realm' => $request->input('buyer_realm'),
            'region' => $raid->region ?: 'EU',
        ]);

        $client->increment('orders_count');
        $client->increment('total_spent', $agreedPrice);

        RaidSignup::create([
            'raid_event_id' => $raid->id,
            'client_id' => $client->id,
            'user_id' => $user->id,
            'advertiser_user_id' => $user->id,
            'character_guid' => 0,
            'character_name' => $request->input('character_name'),
            'buyer_realm' => $request->input('buyer_realm'),
            'buyer_faction' => $request->input('buyer_faction'),
            'agreed_price' => $agreedPrice,
            'role' => $request->input('role'),
            'class' => $request->input('class'),
            'spec' => $request->input('spec'),
            'status' => 'waitlist',
            'notes' => $request->input('notes'),
            'armor_type' => $request->input('armor_type'),
            'loot_priority' => $request->input('loot_priority'),
            'payment_realm' => $request->input('payment_realm'),
            'deposit_amount' => $request->input('deposit_amount', 0),
            'ad_cut' => $adCut,
            'client_discord' => $request->input('client_discord'),
            'is_booster' => false,
        ]);

        $statusMsg = 'Successfully booked client for the run! Waiting for Booster approval.';
        if ($client->type === 'vip') $statusMsg = 'VIP Protocol detected! Booking prioritized. Waiting for approval.';
        if ($client->type === 'unsafe') $statusMsg = 'CAUTION: Unsafe client flag detected. Proceed with extreme vigilance.';

        return redirect()->back()->with('success', $statusMsg);
    }

    public function takeSpot(Request $request, RaidEvent $raid)
    {
        $user = auth()->user();

        // Access Control: Only Boosters or Admins can take booster spots
        if (!in_array($user->account_type, ['booster', 'admin'])) {
            return redirect()->back()->with('error', 'Only verified Boosters can take spots in this run.');
        }

        // Boosters don't need to provide character names, classes, etc. - just their Discord/User identity
        // We'll use their name or a placeholder if character_name is missing.
        $charName = $request->input('character_name', $user->name);
        $role = $request->input('role', 'dps'); 
        $class = $request->input('class', 'Booster');

        // Check if user already has a spot
        $existing = RaidSignup::where('raid_event_id', $raid->id)->where('user_id', $user->id)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'You are already registered for this mission roster.');
        }

        $raid->signups()->create([
            'user_id' => $user->id,
            'character_name' => $charName,
            'role' => $role,
            'class' => $class,
            'spec' => $request->spec,
            'status' => 'pending', 
            'is_booster' => true,
            'agreed_price' => 0,
            'ad_cut' => 0,
            'attendance_status' => 'present',
        ]);

        return redirect()->back()->with('success', 'Application transmitted. Waiting for Commanding Officer approval.');
    }
}
