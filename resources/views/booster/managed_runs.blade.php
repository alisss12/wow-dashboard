@php
    $activeHosted = $hostedRaids->filter(fn($r) => in_array($r->status, ['approved', 'published', 'roster_locked', 'running', 'pending']));
    $pastHosted = $hostedRaids->filter(fn($r) => in_array($r->status, ['completed', 'cancelled', 'expired']));
@endphp

<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:12px">
    <div style="display:flex;align-items:center;gap:20px">
        <div>
            <div class="b-card-title">Mission Control</div>
            <div class="b-card-sub">Operations Command Hub</div>
        </div>
        <div style="display:flex;gap:8px">
            <div style="background:#065f4630;border:1px solid #05966950;padding:6px 14px;border-radius:12px;text-align:center">
                <div style="font-size:16px;font-weight:900;color:#10b981;line-height:1">{{ $activeHosted->count() }}</div>
                <div style="font-size:7px;font-weight:900;color:#059669;text-transform:uppercase;letter-spacing:.1em;margin-top:2px">Active</div>
            </div>
            <div style="background:#1e293b50;border:1px solid #33415550;padding:6px 14px;border-radius:12px;text-align:center">
                <div style="font-size:16px;font-weight:900;color:#94a3b8;line-height:1">{{ $pastHosted->count() }}</div>
                <div style="font-size:7px;font-weight:900;color:#475569;text-transform:uppercase;letter-spacing:.1em;margin-top:2px">Archive</div>
            </div>
        </div>
    </div>
    <a href="{{ route('raids.manage.create') }}?tab=mission_control" class="b-btn-new">
        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
        New Run
    </a>
</div>

{{-- ─── ACTIVE DEPLOYMENTS ─── --}}
<div style="font-size:10px;font-weight:900;color:#6366f1;text-transform:uppercase;letter-spacing:.2em;margin-bottom:20px;display:flex;align-items:center;gap:8px">
    <span style="width:8px;height:8px;background:#6366f1;border-radius:50%;box-shadow:0 0 10px #6366f150"></span>
    Active Deployments
</div>

@forelse($activeHosted as $raid)
    @php
        $isPast      = now()->greaterThan($raid->scheduled_at);
        $isNear      = now()->greaterThanOrEqualTo($raid->scheduled_at->copy()->subMinutes(30));
        $isLocked    = in_array($raid->status, ['running','completed','cancelled']);
        $canCancel   = !$isLocked && !$isPast && !$isNear;
        $clients     = $raid->signups->where('is_booster', false);
    @endphp
    @include('booster.partials.hosted_raid_card', [
        'raid'      => $raid, 
        'clients'   => $clients, 
        'isLocked'  => $isLocked, 
        'canCancel' => $canCancel
    ])
@empty
    <div class="b-card" style="margin-bottom:40px; border-style:dashed; border-color:#1e2535">
        <div class="b-empty" style="padding:60px 24px">
            <div style="font-size:11px;font-weight:800;color:#374151;text-transform:uppercase;letter-spacing:.15em">No active mission protocols detected</div>
            <div style="font-size:10px;color:#1e2535;margin-top:4px">Standby for new deployments or create a manual run</div>
        </div>
    </div>
@endforelse

{{-- ─── MISSION ARCHIVES ─── --}}
@if($pastHosted->count() > 0)
<div style="margin-top:56px">
    <div style="font-size:10px;font-weight:900;color:#374151;text-transform:uppercase;letter-spacing:.2em;margin-bottom:20px;display:flex;align-items:center;gap:8px">
        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 12h14M5 16h14"/></svg>
        Mission Archives
    </div>
    <div style="opacity:0.65">
        @foreach($pastHosted as $raid)
            @php
                $clients = $raid->signups->where('is_booster', false);
            @endphp
            @include('booster.partials.hosted_raid_card', [
                'raid'      => $raid, 
                'clients'   => $clients, 
                'isLocked'  => true, 
                'canCancel' => false
            ])
        @endforeach
    </div>
</div>
@endif
