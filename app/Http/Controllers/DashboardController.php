<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RaidEvent;
use App\Models\RaidSignup;
use App\Models\LedgerTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Entry point for the dashboard.
     * Routes strictly to 2 components based on role.
     */
    public function index(Request $request)
    {
        try {
            $user = auth()->user();
            
            if (!$user->account_type) {
                $user->update(['account_type' => 'user']);
            }

            $component = match (true) {
                in_array($user->account_type, ['booster', 'staff', 'admin']) => 'booster-dashboard',
                $user->account_type === 'advertiser' => 'advertiser-dashboard',
                default => 'booster-dashboard',
            };

            $sharedData = $this->getDashboardData($request);

            return view('layouts.dashboard-layout', [
                'component' => $component,
                'sharedData' => $sharedData
            ]);
        } catch (\Exception $e) {
            Log::error("Dashboard Index Error: " . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Critical terminal error. Re-initializing...');
        }
    }

    /**
     * Aggregates real-world data for all terminals.
     */
    private function getDashboardData(Request $request)
    {
        $user = auth()->user();
        
        // 1. Unified Financial Snapshot
        $balance = $user->balance();
        $recentTransactions = LedgerTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // 2. Marketplace Intel (Available Runs)
        $availableRaids = RaidEvent::where('status', 'approved')
            ->where('scheduled_at', '>', now())
            ->with(['booster:id,name,avatar'])
            ->orderBy('scheduled_at', 'asc')
            ->limit(20)
            ->get()
            ->map(fn($r) => [
                'id' => $r->id,
                'title' => $r->title,
                'instance_name' => $r->instance_name,
                'difficulty' => $r->difficulty,
                'scheduled_at' => $r->scheduled_at?->toIso8601String(),
                'price_per_spot' => (float)$r->price_per_spot,
                'booster' => $r->booster ? ['name' => $r->booster->name, 'avatar' => $r->booster->avatar] : null,
            ])
            ->toArray();

        // 3. Role-Based Data Hubs
        $mySignups = collect();
        $hostedRaids = collect();
        $incomingApplications = collect();
        $pendingRaids = collect();

        if (in_array($user->account_type, ['booster', 'staff', 'admin'])) {
            // My Deployment Status (Where I am boosting)
            $mySignups = RaidSignup::where('user_id', $user->id)
                ->where('is_booster', true)
                ->with('event')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn($s) => [
                    'id' => $s->id,
                    'status' => $s->status,
                    'role' => $s->role,
                    'payout_amount' => (float)$s->payout_amount,
                    'is_paid' => (bool)$s->is_paid,
                    'event' => $s->event ? [
                        'id' => $s->event->id,
                        'title' => $s->event->title,
                        'scheduled_at' => $s->event->scheduled_at?->toIso8601String(),
                    ] : null,
                ]);

            // My Managed Runs (Hosted by me)
            $hostedRaids = RaidEvent::where(function($q) use ($user) {
                    $q->where('booster_user_id', $user->id)
                      ->orWhere('leader_user_id', $user->id)
                      ->orWhere('created_by', $user->id);
                })
                ->with(['signups.user:id,name,avatar'])
                ->orderBy('scheduled_at', 'desc')
                ->get()
                ->map(fn($r) => [
                    'id' => $r->id,
                    'title' => $r->title,
                    'status' => $r->status,
                    'instance_name' => $r->instance_name,
                    'difficulty' => $r->difficulty,
                    'price_per_spot' => (float)$r->price_per_spot,
                    'scheduled_at' => $r->scheduled_at?->toIso8601String(),
                    'signups' => $r->signups->map(fn($s) => [
                        'id' => $s->id,
                        'character_name' => $s->character_name,
                        'role' => $s->role,
                        'status' => $s->status,
                        'user' => $s->user ? ['name' => $s->user->name, 'avatar' => $s->user->avatar] : null,
                    ])->toArray()
                ]);

            // Intel Pipeline (Pending applications for my hosted raids)
            $myHostedIds = RaidEvent::where('booster_user_id', $user->id)->pluck('id');
            $incomingApplications = RaidSignup::whereIn('raid_event_id', $myHostedIds)
                ->where('status', 'pending')
                ->with(['event', 'user:id,name,avatar'])
                ->get()
                ->map(fn($s) => [
                    'id' => $s->id,
                    'character_name' => $s->character_name,
                    'user' => $s->user ? ['name' => $s->user->name, 'avatar' => $s->user->avatar] : null,
                    'event' => $s->event ? ['title' => $s->event->title] : null,
                ]);

            // NEW: Pending Mission Audit (Advertiser requests needing approval)
            if (in_array($user->account_type, ['staff', 'admin'])) {
                $pendingRaids = RaidEvent::where('status', 'pending')
                    ->with('requestedBy:id,name,avatar')
                    ->orderBy('created_at', 'asc')
                    ->get()
                    ->map(fn($r) => [
                        'id' => $r->id,
                        'title' => $r->title,
                        'instance_name' => $r->instance_name,
                        'difficulty' => $r->difficulty,
                        'price_per_spot' => (float)$r->price_per_spot,
                        'scheduled_at' => $r->scheduled_at?->toIso8601String(),
                        'requested_by' => $r->requestedBy ? ['name' => $r->requestedBy->name] : null,
                    ]);
            }
        }

        if ($user->account_type === 'advertiser') {
            // My Client Manifest
            $mySignups = RaidSignup::where('advertiser_user_id', $user->id)
                ->with('event')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn($s) => [
                    'id' => $s->id,
                    'character_name' => $s->character_name,
                    'buyer_realm' => $s->buyer_realm,
                    'status' => $s->status,
                    'agreed_price' => (float)$s->agreed_price,
                    'ad_cut' => (float)$s->ad_cut,
                    'is_paid' => (bool)$s->is_paid,
                    'event' => $s->event ? [
                        'id' => $s->event->id,
                        'title' => $s->event->title,
                        'scheduled_at' => $s->event->scheduled_at?->toIso8601String(),
                    ] : null,
                ]);
        }

        // 4. Mission Stats
        $stats = [
            'balance' => (float)$balance,
            'active_orders' => $mySignups->where('status', 'accepted')->where('is_paid', false)->count(),
            'hosted_runs' => $hostedRaids->count(),
            'pending_intel' => $incomingApplications->count(),
            'total_volume' => (float)$mySignups->sum('agreed_price'),
            
            // Fix for Undefined array key errors in views
            'total_sales' => (float)$mySignups->sum('agreed_price'),
            'active_bookings' => $mySignups->where('status', 'accepted')->count(),
            'commission_earned' => (float)$mySignups->sum('ad_cut'),
            'active_runs' => $hostedRaids->count(),
            'total_earnings' => (float)$balance,
            'completion_rate' => 100, // Tactical baseline
        ];

        // 5. Payment Cycle Logic
        $pendingGold = (float)RaidSignup::where('user_id', $user->id)
            ->where('is_paid', false)
            ->sum('payout_amount');

        $paymentCycle = [
            'pending_gold' => $pendingGold,
            'next_payout' => now()->next(Carbon::WEDNESDAY)->setTime(10, 0)->toIso8601String(),
        ];

        return [
            'stats' => $stats,
            'availableRaids' => $availableRaids,
            'mySignups' => $mySignups->toArray(),
            'hostedRaids' => $hostedRaids->toArray(),
            'incomingApplications' => $incomingApplications->toArray(),
            'pendingRaids' => $pendingRaids->toArray(),
            'recentTransactions' => $recentTransactions->toArray(),
            'paymentCycle' => $paymentCycle,
        ];
    }
}
