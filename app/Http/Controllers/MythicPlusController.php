<?php

namespace App\Http\Controllers;

use App\Models\RaidEvent;
use App\Models\RaidSignup;
use App\Models\BoostingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MythicPlusController extends Controller
{
    public function storeRequest(Request $request)
    {
        $request->validate([
            'service_id'       => 'required',
            'character_name'   => 'required|string',
            'buyer_realm'      => 'required|string',
            'mythic_plus_level'=> 'required|string',
            'price'            => 'required|numeric',
            'timing_type'      => 'nullable|string|in:live,scheduled',
            'scheduled_at'     => 'nullable|date',
        ]);

        // Support both DB-linked services and hardcoded string fallbacks
        $service = is_numeric($request->service_id)
            ? BoostingService::find($request->service_id)
            : null;

        $serviceType = $service ? $service->name : $request->service_id;
        
        $timingType = $request->input('timing_type', 'live');
        $scheduledAt = ($timingType === 'scheduled' && $request->scheduled_at) 
            ? $request->scheduled_at 
            : now();

        $event = RaidEvent::create([
            'title'            => 'M+' . $request->mythic_plus_level . ' Request',
            'instance_name'    => 'Pending Dungeon',
            'difficulty'       => 'Mythic',
            'region'           => $request->input('region', 'EU'),
            'service_category' => 'Mythic+',
            'service_type'     => $serviceType,
            'service_id'       => $service?->id,
            'price_per_spot'   => $request->price,
            'max_players'      => 5,
            'status'           => 'queued',
            'is_queue'         => true,
            'mythic_plus_level'=> $request->mythic_plus_level,
            'scheduled_at'     => $scheduledAt,
            'timing_type'      => $timingType,
            'created_by'       => Auth::id(),
            'requested_by_user_id' => Auth::id(),
        ]);

        // Create the client signup record — user_id is the advertiser who placed the order
        RaidSignup::create([
            'raid_event_id'    => $event->id,
            'user_id'          => Auth::id(),
            'character_name'   => $request->character_name,
            'buyer_realm'      => $request->buyer_realm,
            'agreed_price'     => $request->price,
            'advertiser_user_id' => Auth::id(),
            'ad_cut'           => $request->price * 0.10,
            'status'           => 'accepted',
            'is_booster'       => false,
            'role'             => 'client',
            'class'            => 'Client',
            'attendance_status'=> 'present',
        ]);
        
        // Notify Admins
        $admins = \App\Models\User::whereIn('account_type', ['admin', 'staff'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\NewMplusOrder($event));
        }

        return back()->with('success', 'M+ request added to the live queue.');
    }

    public function joinGroup(Request $request, RaidEvent $event)
    {
        if (!$event->is_queue) {
            return back()->with('error', 'This is not a queue-based run.');
        }

        $request->validate([
            'character_name' => 'nullable|string',
            'role' => 'nullable|in:tank,healer,dps',
            'class' => 'nullable|string',
            'notes' => 'nullable|string|max:2000',
        ]);

        // Check if already applied
        $existing = RaidSignup::where('raid_event_id', $event->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($existing) {
            return back()->with('error', 'You have already applied for this M+ squad.');
        }

        // Check if full
        $boosterCount = $event->signups()->where('is_booster', true)->where('status', 'accepted')->count();
        if ($boosterCount >= 4) {
            return back()->with('error', 'This group is already full.');
        }

        $isFirst = ($boosterCount === 0);

        RaidSignup::create([
            'raid_event_id' => $event->id,
            'user_id' => Auth::id(),
            'character_name' => $request->input('character_name', Auth::user()->name),
            'role' => $request->input('role', 'dps'),
            'class' => $request->input('class', 'Booster'),
            'status' => 'pending',
            'is_booster' => true,
            'notes' => $request->input('notes'),
            'attendance_status' => 'present',
            'group_invite_code' => $request->group_invite_code,
        ]);

        if ($isFirst) {
            $event->update([
                'status' => 'scouting',
                'leader_user_id' => Auth::id(),
                'leader_name' => Auth::user()->name,
            ]);
        }

        // Notify Advertiser (RequestedBy) and Admins/Staff
        $advertiser = $event->requestedBy;
        $notes = $request->input('notes', '');
        if ($advertiser) {
            $advertiser->notify(new \App\Notifications\BoosterAppliedToSquad($event, Auth::user(), $notes));
        }

        $admins = \App\Models\User::whereIn('account_type', ['admin', 'staff'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\BoosterAppliedToSquad($event, Auth::user(), $notes));
        }

        return back()->with('success', 'M+ Squad application submitted.');
    }
}
