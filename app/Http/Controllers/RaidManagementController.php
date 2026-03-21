<?php

namespace App\Http\Controllers;

use App\Models\RaidEvent;
use App\Notifications\BoosterApplicationAccepted;
use App\Notifications\BoosterApplicationDeclined;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RaidManagementController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        if (!in_array($user->account_type, ['booster', 'admin'])) {
            abort(403);
        }

        $services = \App\Models\BoostingService::where('is_active', true)->get();

        return view('raids.create', compact('services'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->account_type, ['booster', 'admin'])) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'instance_name' => 'required|string|max:255',
            'difficulty' => 'required|string|max:100',
            'scheduled_at' => 'required|date|after:now',
            'duration_hours' => 'required|integer|min:1|max:12',
            'max_players' => 'required|integer|min:1|max:40',
            'price_per_spot' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'service_category' => 'nullable|string|in:Raid,Mythic+,PvP,Leveling,Gold Track,Delves',
            'service_type' => 'nullable|string|max:100',
            'region' => 'nullable|string|in:EU,NA',
            'coordinator_discord' => 'nullable|string|max:100',
            'cloth_spots' => 'nullable|integer|min:0',
            'leather_spots' => 'nullable|integer|min:0',
            'mail_spots' => 'nullable|integer|min:0',
            'plate_spots' => 'nullable|integer|min:0',
            'bosses_total' => 'nullable|integer|min:0',
            'bosses_killed' => 'nullable|integer|min:0',
            'service_id' => 'nullable|exists:boosting_services,id',
        ]);

        $raid = new RaidEvent($validated);
        $raid->booster_user_id = $user->id;
        $raid->leader_user_id = $user->id;
        $raid->leader_name = $user->name;
        $raid->created_by = $user->id;
        $raid->status = in_array($user->account_type, ['admin', 'staff']) ? 'approved' : 'pending';
        $raid->service_category = $request->input('service_category', 'Raid');
        $raid->region = $request->input('region', 'EU');
        $raid->save();

        return redirect()->route('dashboard', ['tab' => request('tab', 'my_runs')])->with('status', 'Raid run submitted for approval.');
    }

    public function edit(RaidEvent $raid)
    {
        $user = Auth::user();
        
        // Boosters can only edit their own raids, and only if they are not yet started.
        if ($raid->booster_user_id !== $user->id && $user->account_type !== 'admin') {
            abort(403);
        }

        return view('raids.edit', compact('raid'));
    }

    public function update(Request $request, RaidEvent $raid)
    {
        $user = Auth::user();
        
        if ($raid->booster_user_id !== $user->id && $user->account_type !== 'admin') {
            abort(403);
        }

        // Professional site rule: Cannot edit if running or completed
        if (in_array($raid->status, [RaidEvent::STATUS_RUNNING, RaidEvent::STATUS_COMPLETED])) {
            return redirect()->back()->with('error', 'Cannot edit a run that is currently active or completed.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'instance_name' => 'required|string|max:255',
            'difficulty' => 'required|string|max:100',
            'scheduled_at' => 'required|date',
            'duration_hours' => 'required|integer|min:1|max:12',
            'max_players' => 'required|integer|min:1|max:40',
            'price_per_spot' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'service_category' => 'nullable|string|in:Raid,Mythic+,PvP,Leveling,Gold Track,Delves',
            'service_type' => 'nullable|string|max:100',
            'region' => 'nullable|string|in:EU,NA',
            'coordinator_discord' => 'nullable|string|max:100',
            'cloth_spots' => 'nullable|integer|min:0',
            'leather_spots' => 'nullable|integer|min:0',
            'mail_spots' => 'nullable|integer|min:0',
            'plate_spots' => 'nullable|integer|min:0',
            'bosses_total' => 'nullable|integer|min:0',
            'bosses_killed' => 'nullable|integer|min:0',
            'dynamic_slots' => 'nullable|array',
            'service_id' => 'nullable|exists:boosting_services,id',
        ]);

        $raid->update($validated);

        return redirect()->back()->with('status', 'Raid run updated successfully.');
    }

    public function cancel(Request $request, RaidEvent $raid)
    {
        $user = Auth::user();
        if ($raid->booster_user_id !== $user->id && $user->account_type !== 'admin') abort(403);

        if ($raid->status === RaidEvent::STATUS_RUNNING) {
            return redirect()->back()->with('error', 'Cannot cancel a run that is already running.');
        }

        // 30-minute lockout & Expiry Check (Admins/Staff bypass)
        if (!in_array($user->account_type, ['admin', 'staff'])) {
            $isPast = now()->greaterThan($raid->scheduled_at);
            $isNear = now()->greaterThanOrEqualTo($raid->scheduled_at->copy()->subMinutes(30));

            if ($isPast) {
                return redirect()->back()->with('error', 'Protocol Lock: This mission has already expired. Cancellation is restricted for boosters.');
            }

            if ($isNear) {
                return redirect()->back()->with('error', 'Protocol Lock: This mission is too close to deployment (30m lockout). Cancellation is restricted for boosters.');
            }
        }

        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        // Centralized cancellation with notifications
        $raid->cancelRun($request->reason);

        return redirect()->route('dashboard', ['tab' => request('tab', 'my_runs')])->with('status', 'Run has been cancelled and stakeholders notified.');
    }

    public function duplicate(RaidEvent $raid)
    {
        $user = Auth::user();
        if ($raid->booster_user_id !== $user->id && $user->account_type !== 'admin') abort(403);

        $newRaid = $raid->replicate();
        $newRaid->status = RaidEvent::STATUS_DRAFT; // Cloned runs start as draft
        $newRaid->scheduled_at = $raid->scheduled_at->addDays(7); // Default to same time next week
        $newRaid->save();

        return redirect()->route('dashboard', ['tab' => request('tab', 'my_runs')])->with('status', 'Run duplicated safely (scheduled for next week).');
    }

    public function lockRoster(RaidEvent $raid)
    {
        if (Auth::id() !== $raid->leader_user_id && Auth::user()->account_type !== 'admin') abort(403);
        $raid->lockRoster();
        return redirect()->back()->with('status', 'Operation Roster Locked. No more personnel changes permitted.');
    }

    public function start(RaidEvent $raid)
    {
        if (Auth::id() !== $raid->leader_user_id && Auth::user()->account_type !== 'admin') abort(403);
        $raid->startRun();
        return redirect()->back()->with('status', 'Mission started. Good luck boosters!');
    }

    public function complete(RaidEvent $raid)
    {
        if (Auth::id() !== $raid->leader_user_id && Auth::user()->account_type !== 'admin') abort(403);
        $raid->completeRun();
        return redirect()->back()->with('status', 'Mission completed. Gold distribution processed.');
    }

    public function toggleAttendance(RaidEvent $raid, \App\Models\RaidSignup $signup)
    {
        if (Auth::id() !== $raid->leader_user_id && Auth::user()->account_type !== 'admin') abort(403);
        if ($signup->raid_event_id !== $raid->id || !$signup->is_booster) abort(404);

        $newStatus = ($signup->attendance_status === 'present') ? 'absent' : 'present';
        $signup->update(['attendance_status' => $newStatus]);

        return redirect()->back()->with('status', "Attendance for {$signup->character_name} updated to {$newStatus}.");
    }

    public function approveBooster(RaidEvent $raid, \App\Models\RaidSignup $signup)
    {
        if (Auth::id() !== $raid->leader_user_id && Auth::user()->account_type !== 'admin') abort(403);
        if ($signup->raid_event_id !== $raid->id || !$signup->is_booster) abort(404);

        $signup->update(['status' => 'accepted']);

        // Notify the accepted booster
        if ($signup->user) {
            $signup->user->notify(new BoosterApplicationAccepted($raid));
        }

        // Auto-Reject logic
        if ($raid->service_category === 'Mythic+') {
            $acceptedCount = $raid->signups()->where('is_booster', true)->where('status', 'accepted')->count();
            if ($acceptedCount >= 4) {
                $raid->update(['status' => 'running']);
                // Decline remaining pending boosters and notify each
                $declined = $raid->signups()->where('is_booster', true)->where('status', 'pending')->with('user')->get();
                $raid->signups()->where('is_booster', true)->where('status', 'pending')->update(['status' => 'declined']);
                foreach ($declined as $d) {
                    if ($d->user) $d->user->notify(new BoosterApplicationDeclined($raid));
                }
            }
        } else {
            $totalAccepted = $raid->signups()->where('status', 'accepted')->count();
            if ($totalAccepted >= $raid->max_players) {
                $raid->update(['status' => 'full']);
                // Decline remaining pending and notify
                $declined = $raid->signups()->where('status', 'pending')->with('user')->get();
                $raid->signups()->where('status', 'pending')->update(['status' => 'declined']);
                foreach ($declined as $d) {
                    if ($d->user) $d->user->notify(new BoosterApplicationDeclined($raid));
                }
            }
        }

        return redirect()->back()->with('status', "Booster {$signup->character_name} authorized.");
    }

    public function rejectBooster(RaidEvent $raid, \App\Models\RaidSignup $signup)
    {
        if (Auth::id() !== $raid->leader_user_id && Auth::user()->account_type !== 'admin') abort(403);
        if ($signup->raid_event_id !== $raid->id || !$signup->is_booster) abort(404);

        $signup->update(['status' => 'declined']);

        // Notify the declined booster
        if ($signup->user) {
            $signup->user->notify(new BoosterApplicationDeclined($raid));
        }

        return redirect()->back()->with('status', "Booster application for {$signup->character_name} declined.");
    }

    public function addBooster(Request $request, RaidEvent $raid)
    {
        if (Auth::id() !== $raid->leader_user_id && Auth::user()->account_type !== 'admin') abort(403);
        
        $request->validate(['user_id' => 'required|exists:users,id']);
        $boosterUser = \App\Models\User::find($request->user_id);

        if ($boosterUser->account_type !== 'booster') {
            return redirect()->back()->with('error', 'Selected user is not a verified booster.');
        }

        // Check if already in raid
        if ($raid->signups()->where('user_id', $boosterUser->id)->exists()) {
            return redirect()->back()->with('error', 'Booster is already signed up for this mission.');
        }

        \App\Models\RaidSignup::create([
            'raid_event_id' => $raid->id,
            'user_id' => $boosterUser->id,
            'character_name' => $boosterUser->name,
            'role' => 'dps', // Default role
            'status' => 'accepted',
            'is_booster' => true,
            'attendance_status' => 'present'
        ]);

        return redirect()->back()->with('status', "Booster {$boosterUser->name} added to the squad.");
    }

    public function removeBooster(RaidEvent $raid, \App\Models\RaidSignup $signup)
    {
        if (Auth::id() !== $raid->leader_user_id && Auth::user()->account_type !== 'admin') abort(403);
        
        if ($signup->raid_event_id !== $raid->id || !$signup->is_booster) abort(404);

        $signup->delete();

        return redirect()->back()->with('status', 'Booster removed from the roster.');
    }

    public function reportIssue(Request $request, RaidEvent $raid)
    {
        if (Auth::id() !== $raid->leader_user_id && Auth::user()->account_type !== 'admin') abort(403);
        
        $request->validate(['content' => 'required|string']);
        
        \App\Models\RunNote::create([
            'raid_event_id' => $raid->id,
            'user_id' => Auth::id(),
            'type' => 'admin',
            'content' => $request->content,
        ]);

        return redirect()->back()->with('status', 'Issue reported to Command Center.');
    }
}
