@if($type === 'available')

<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px">
    <div>
        <div class="b-card-title">Mission Deployment Hub</div>
        <div class="b-card-sub">{{ $availableRaids->count() }} active protocols found</div>
    </div>
</div>

<div class="b-card" style="overflow:hidden">
    @forelse($availableRaids as $raid)
    @php $roles = $raid->getRoleCounts(); @endphp
    <div class="b-run">
        {{-- date --}}
        <div class="b-run-date">
            <div class="b-run-date-m">{{ $raid->scheduled_at->format('M') }}</div>
            <div class="b-run-date-d">{{ $raid->scheduled_at->format('j') }}</div>
            <div class="b-run-date-t">{{ $raid->scheduled_at->format('H:i') }}</div>
        </div>

        {{-- info --}}
        <div class="b-run-info">
            <div class="b-run-title">{{ $raid->title }}</div>
            <div class="b-run-meta">
                {{ $raid->instance_name }}
                @if($raid->difficulty) · <span>{{ $raid->difficulty }}</span> @endif
                @if($raid->region) · <span>{{ strtoupper($raid->region) }}</span> @endif
            </div>
        </div>

        {{-- roles --}}
        <div class="b-roles">
            <div class="b-role">
                <div class="b-role-lbl" style="color:#60a5fa">Tank</div>
                <div class="b-role-val {{ $roles['tank'] >= ($raid->required_tanks ?: 2) ? 'full' : '' }}">{{ $roles['tank'] }}/{{ $raid->required_tanks ?: 2 }}</div>
            </div>
            <div class="b-role">
                <div class="b-role-lbl" style="color:#4ade80">Heal</div>
                <div class="b-role-val {{ $roles['healer'] >= ($raid->required_healers ?: 5) ? 'full' : '' }}">{{ $roles['healer'] }}/{{ $raid->required_healers ?: 5 }}</div>
            </div>
            <div class="b-role">
                <div class="b-role-lbl" style="color:#f87171">DPS</div>
                <div class="b-role-val {{ $roles['dps'] >= ($raid->required_dps ?: 13) ? 'full' : '' }}">{{ $roles['dps'] }}/{{ $raid->required_dps ?: 13 }}</div>
            </div>
        </div>

        {{-- reward --}}
        <div style="flex-shrink:0;text-align:right">
            <div style="font-size:9px;font-weight:700;color:#374151;text-transform:uppercase;margin-bottom:2px">Reward</div>
            <div style="font-size:16px;font-weight:900;color:#22c55e;font-family:monospace">{{ number_format($raid->price_per_spot) }}g</div>
        </div>

        {{-- cta --}}
        @php
            $mySignup = $raid->signups->where('user_id', auth()->id())->first();
        @endphp

        @if($mySignup)
            @if($mySignup->status === 'accepted')
                <div style="background:#22c55e15;border:1px solid #22c55e30;color:#22c55e;padding:8px 20px;border-radius:12px;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:.1em">
                    ✓ Mission Accepted
                </div>
            @else
                <div style="background:#f59e0b15;border:1px solid #f59e0b30;color:#f59e0b;padding:8px 20px;border-radius:12px;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:.1em">
                    ● Application Pending
                </div>
            @endif
        @else
            <a href="{{ route('raids.show', $raid->id) }}" class="b-btn-signup">Sign Up</a>
        @endif
    </div>
    @empty
    <div class="b-empty">
        <div class="b-empty-icon">
            <svg width="24" height="24" fill="none" stroke="#374151" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div style="font-size:12px;font-weight:800;color:#374151;text-transform:uppercase;letter-spacing:.1em">No active protocols</div>
        <div style="font-size:11px;color:#1f2937">Check back later for available runs</div>
    </div>
    @endforelse
</div>

@else

{{-- MY SCHEDULE --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px">
    <div>
        <div class="b-card-title">My Mission Schedule</div>
        <div class="b-card-sub">{{ $mySignups->where('is_booster', true)->count() }} assignments</div>
    </div>
</div>

@forelse($mySignups->where('is_booster', true) as $signup)
@php $raid = $signup->event; @endphp
<div class="b-sched">
    <div class="b-sched-head">
        <div style="display:flex;align-items:center;gap:12px;min-width:0">
            <div style="width:36px;height:36px;border-radius:8px;background:#1e1b4b;border:1px solid #312e81;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:14px;color:#818cf8;flex-shrink:0;font-style:italic">
                {{ substr($raid->instance_name, 0, 1) }}
            </div>
            <div style="min-width:0">
                <div style="font-size:13px;font-weight:800;color:#fff;text-transform:uppercase;font-style:italic;truncate">{{ $raid->title }}</div>
                <div style="font-size:10px;color:#4b5563;font-weight:600;margin-top:2px">
                    {{ $raid->instance_name }} ·
                    <span style="color:#6366f1">{{ $raid->scheduled_at->format('D, M j — H:i') }}</span>
                    @if($raid->scheduled_at->isPast())
                        · <span style="color:#ef4444">Expired</span>
                    @endif
                </div>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:8px;flex-shrink:0">
            <a href="{{ route('raids.show', $raid->id) }}" class="b-act b-act-ghost">View Hub</a>
            @php
                $sColor = match($signup->status) {
                    'accepted' => '#22c55e',
                    'pending' => '#f59e0b',
                    'declined' => '#ef4444',
                    default => '#4b5563'
                };
            @endphp
            <span class="badge-status" style="background: {{ $sColor }}20; color: {{ $sColor }}; border: 1px solid {{ $sColor }}40; padding: 4px 12px; border-radius: 12px; font-size: 10px; font-weight: 800; text-transform: uppercase;">{{ $signup->status }}</span>
        </div>
    </div>
    <div class="b-sched-cols">
        <div class="b-sched-col">
            <div class="b-col-lbl">Role</div>
            <div style="font-size:13px;font-weight:800;color:#fff;text-transform:uppercase">{{ $signup->role }}</div>
            <div style="font-size:11px;color:#374151">{{ $signup->character_name }}</div>
        </div>
        <div class="b-sched-col">
            <div class="b-col-lbl">Expected Payout</div>
            @php
                // Estimated payout for a 5-man M+ run (20% share) or Raid
                $estPayout = $raid->price_per_spot * 0.20;
            @endphp
            <div style="font-size:13px;font-weight:800;color:#22c55e;font-family:monospace">{{ number_format($estPayout) }}g</div>
        </div>
        <div class="b-sched-col">
            <div class="b-col-lbl">Roster Status</div>
            @if($raid->status === 'scheduled')
                <div style="display:flex;align-items:center;gap:6px">
                    <span style="width:7px;height:7px;border-radius:50%;background:#f59e0b;display:inline-block;animation:pulse 2s infinite"></span>
                    <span style="font-size:12px;font-weight:800;color:#f59e0b;text-transform:uppercase">Awaiting Lock</span>
                </div>
            @else
                <div style="display:flex;align-items:center;gap:6px">
                    <span style="width:7px;height:7px;border-radius:50%;background:#22c55e;display:inline-block"></span>
                    <span style="font-size:12px;font-weight:800;color:#22c55e;text-transform:uppercase">Verified</span>
                </div>
            @endif
        </div>
    </div>
</div>
@empty
<div class="b-card">
    <div class="b-empty">
        <div class="b-empty-icon">
            <svg width="24" height="24" fill="none" stroke="#374151" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div style="font-size:12px;font-weight:800;color:#374151;text-transform:uppercase;letter-spacing:.1em">No active assignments</div>
        <div style="font-size:11px;color:#1f2937">Sign up for a run in Available Runs</div>
    </div>
</div>
@endforelse

@endif
