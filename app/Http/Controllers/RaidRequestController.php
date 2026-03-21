<?php

namespace App\Http\Controllers;

use App\Models\RaidEvent;
use App\Models\RaidSignup;
use App\Notifications\BoosterApplicationAccepted;
use App\Notifications\BoosterApplicationDeclined;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RaidRequestController extends Controller
{
    /**
     * Advertiser: view their submitted requests
     */
    public function myRequests()
    {
        $requests = RaidEvent::where('requested_by_user_id', Auth::id())
            ->with(['signups' => fn($q) => $q->where('is_booster', true)])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('raid-requests.my-requests', compact('requests'));
    }

    /**
     * Advertiser: submit a custom raid request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'raid_type'            => 'required|string|max:100',
            'difficulty'           => 'required|string',
            'preferred_start_time' => 'required|date|after:now',
            'client_slots'         => 'required|integer|min:1|max:25',
            'armor_stack'          => 'nullable|string|max:50',
            'region'               => 'nullable|string|in:EU,US,KR,TW',
            'special_conditions'   => 'nullable|string|max:2000',
            'applications_close_at'=> 'nullable|date|after:now|before:preferred_start_time',
            'timing_type'          => 'nullable|string|in:live,scheduled,flexible',
            'flexible_time_note'   => 'nullable|string|max:1000',
        ]);

        $difficulty = ($validated['raid_type'] === 'Mythic+') ? 'Mythic' : $validated['difficulty'];
        $maxPlayers = ($validated['raid_type'] === 'Mythic+') ? 5 : ($validated['client_slots'] + 5);
        $isQueue = ($validated['raid_type'] === 'Mythic+');

        RaidEvent::create([
            'title'                 => "Custom {$validated['raid_type']} Request — by " . Auth::user()->name,
            'instance_name'         => $validated['raid_type'],
            'service_category'      => $isQueue ? 'Mythic+' : 'Raid',
            'difficulty'            => $difficulty,
            'region'                => $validated['region'] ?? 'EU',
            'scheduled_at'          => $validated['preferred_start_time'],
            'preferred_start_time'  => $validated['preferred_start_time'],
            'max_players'           => $maxPlayers,
            'is_queue'              => $isQueue,
            'armor_stack'           => $validated['armor_stack'],
            'special_conditions'    => $validated['special_conditions'],
            'applications_close_at' => $validated['applications_close_at'],
            'status'                => 'requested',
            'creation_source'       => 'advertiser_request',
            'requested_by_user_id'  => Auth::id(),
            'created_by'            => Auth::id(),
            'timing_type'           => $validated['timing_type'] ?? 'scheduled',
            'flexible_time_note'    => $validated['flexible_time_note'],
        ]);

        return back()->with('success', 'Your custom raid request has been submitted. Admin will review it shortly.');
    }

    /**
     * Raid Leader: view all open requests available to apply for
     */
    public function index()
    {
        $openRequests = RaidEvent::where('status', 'open_for_applications')
            ->where(function ($q) {
                $q->whereNull('applications_close_at')
                  ->orWhere('applications_close_at', '>', now());
            })
            ->with(['requestedBy', 'signups' => fn($q) => $q->where('is_booster', true)])
            ->orderBy('preferred_start_time', 'asc')
            ->get();

        // Check which ones the current user already applied for
        $myApplicationIds = RaidSignup::where('user_id', Auth::id())
            ->where('is_booster', true)
            ->pluck('raid_event_id')
            ->toArray();

        return view('raid-requests.index', compact('openRequests', 'myApplicationIds'));
    }

    /**
     * Raid Leader: apply for a request
     */
    public function apply(Request $request, RaidEvent $raidRequest)
    {
        if ($raidRequest->status !== 'open_for_applications') {
            return back()->with('error', 'This request is no longer accepting applications.');
        }

        if ($raidRequest->applications_close_at && now()->isAfter($raidRequest->applications_close_at)) {
            return back()->with('error', 'The application deadline has passed.');
        }

        // Prevent duplicate applications
        $existing = RaidSignup::where('raid_event_id', $raidRequest->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($existing) {
            return back()->with('error', 'You have already applied for this raid request.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:2000',
        ]);

        RaidSignup::create([
            'raid_event_id'  => $raidRequest->id,
            'user_id'        => Auth::id(),
            'character_name' => Auth::user()->name,
            'role'           => $request->input('role', 'dps'),
            'class'          => $request->input('class', 'Raid Leader'),
            'status'         => 'pending',
            'is_booster'     => true,
            'notes'          => $validated['notes'],
            'attendance_status' => 'present',
        ]);

        // Notify Advertiser (RequestedBy) and Admins/Staff
        if ($raidRequest->requestedBy) {
            $raidRequest->requestedBy->notify(new \App\Notifications\BoosterAppliedToSquad($raidRequest, Auth::user(), $validated['notes'] ?? ''));
        }

        $admins = \App\Models\User::whereIn('account_type', ['admin', 'staff'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\BoosterAppliedToSquad($raidRequest, Auth::user(), $validated['notes'] ?? ''));
        }

        return back()->with('success', 'Application submitted. The admin will review all applications and notify you of the decision.');
    }

    /**
     * Admin: publish a requested raid to raid leaders
     */
    public function publish(RaidEvent $raidRequest)
    {
        if (Auth::user()->account_type !== 'admin') abort(403);

        $raidRequest->update(['status' => 'open_for_applications']);

        return back()->with('status', "Raid Request #{$raidRequest->id} is now open for raid leader applications.");
    }

    /**
     * Admin: assign one raid leader, notify all applicants
     */
    public function assignLeader(RaidEvent $raidRequest, RaidSignup $signup)
    {
        // Must be admin OR the one who created/requested this run
        $isOwner = (Auth::id() === $raidRequest->created_by || Auth::id() === $raidRequest->requested_by_user_id);
        if (Auth::user()->account_type !== 'admin' && !$isOwner) {
            abort(403);
        }

        DB::transaction(function () use ($raidRequest, $signup) {
            // Accept the selected leader/booster
            $signup->update(['status' => 'accepted']);

            $isMplus = ($raidRequest->is_queue || $raidRequest->service_category === 'Mythic+');
            
            // For M+, we need 4 boosters. For regular raids, we assign ONE leader.
            if ($isMplus) {
                $acceptedCount = $raidRequest->signups()->where('is_booster', true)->where('status', 'accepted')->count();
                $newStatus = ($acceptedCount >= 4) ? 'running' : 'scouting';
            } else {
                $newStatus = 'assigned';
            }

            // Update the raid event
            if (!$raidRequest->booster_user_id) {
                $raidRequest->update([
                    'assigned_leader_id' => $signup->user_id,
                    'booster_user_id'    => $signup->user_id, // Primary contact
                    'leader_user_id'     => $signup->user_id,
                    'leader_name'        => $signup->user?->name,
                ]);
            }

            $raidRequest->update(['status' => $newStatus]);

            // Auto-reject others ONLY if NOT Mythic+ (where we need multiple) 
            // OR if M+ is now full
            if (!$isMplus || $newStatus === 'running') {
                $others = $raidRequest->signups()
                    ->where('is_booster', true)
                    ->where('status', 'pending')
                    ->where('id', '!=', $signup->id)
                    ->get();

                foreach ($others as $other) {
                    $other->update(['status' => 'declined']);
                    if ($other->user) {
                        $other->user->notify(new BoosterApplicationDeclined($raidRequest));
                    }
                }
            }

            // Notify selected booster
            if ($signup->user) {
                $signup->user->notify(new BoosterApplicationAccepted($raidRequest));
            }

            // Notify solicitor (advertiser)
            if ($raidRequest->requestedBy) {
                $raidRequest->requestedBy->notify(new \App\Notifications\OrderSquadSelected($raidRequest));
            }
        });

        return back()->with('success', "Squad assigned! The operation is now active and visible in the 'Live Runs' tab.");
    }
}
