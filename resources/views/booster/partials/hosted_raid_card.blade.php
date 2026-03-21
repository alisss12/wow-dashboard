@php
    $isCancelled = $raid->status === 'cancelled';
@endphp

<div class="b-hosted {{ $isCancelled ? 'is-cancelled' : '' }}">
    {{-- ── header row ── --}}
    <div class="b-hosted-head">
        <div class="b-hosted-meta">
            <div class="b-hosted-icon">{{ substr($raid->instance_name, 0, 1) }}</div>
            <div style="min-width:0">
                <div style="font-size:14px;font-weight:800;color:#fff;text-transform:uppercase;font-style:italic;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                    {{ $raid->title }}
                </div>
                <div style="font-size:10px;color:#4b5563;font-weight:600;margin-top:3px">
                    {{ $raid->instance_name }}
                    ({{ $raid->difficulty }}) ·
                    <span style="color:{{ $isCancelled ? '#ef4444' : '#6366f1' }}">{{ $raid->scheduled_at->format('D, M j — H:i') }}</span>
                </div>
            </div>
        </div>

        {{-- action bar --}}
        <div class="b-actions">
            @php
                $statusColors = [
                    'draft' => 'background:#1e293b; color:#94a3b8; border-color:#334155',
                    'approved' => 'background:#064e3b; color:#10b981; border-color:#065f46',
                    'roster_locked' => 'background:#1e1b4b; color:#818cf8; border-color:#312e81',
                    'running' => 'background:#4c1d95; color:#a78bfa; border-color:#5b21b6',
                    'completed' => 'background:#14532d; color:#22c55e; border-color:#166534',
                    'cancelled' => 'background:#450a0a; color:#f87171; border-color:#7f1d1d',
                ];
                $style = $statusColors[$raid->status] ?? 'background:#1e1b4b; color:#818cf8; border-color:#312e81';
            @endphp
            <span class="badge" style="{{ $style }}">{{ strtoupper($raid->status) }}</span>

            @if(!$isLocked)
            <a href="{{ route('raids.manage.edit', $raid->id) }}&tab=mission_control" class="b-act b-act-ghost">
                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            @endif

            <form action="{{ route('raids.manage.duplicate', $raid->id) }}" method="POST" style="display:inline">
                @csrf
                <input type="hidden" name="tab" value="mission_control">
                <button type="submit" class="b-act b-act-ghost">
                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2"/></svg>
                    Duplicate
                </button>
            </form>

            @if($canCancel)
            <button x-data @click="$dispatch('open-cancel-modal',{raidId:{{ $raid->id }},title:'{{ addslashes($raid->title) }}'})"
                    class="b-act b-act-red">
                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Cancel
            </button>
            @elseif(!$isLocked)
            <span class="b-act b-act-lock" title="{{ $isPast ? 'Run expired' : 'Within 30 min cutoff' }}">🔒 Locked</span>
            @endif

            <a href="{{ route('raids.show', $raid->id) }}?tab=mission_control" class="b-act b-act-primary">
                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                Manage
            </a>
        </div>
    </div>

    {{-- ── quick stats ── --}}
    <div class="b-hosted-stats" style="border-top:1px solid #1e2535;border-bottom:{{ $clients->count() > 0 ? '1px solid #1e2535' : 'none' }}">
        <div class="b-hosted-stat">
            <div class="b-hosted-stat-lbl">Clients</div>
            <div class="b-hosted-stat-val" style="color:#fff">{{ $clients->count() }}<span style="color:#374151;font-size:14px">/{{ $raid->max_players ?? 24 }}</span></div>
        </div>
        <div class="b-hosted-stat">
            <div class="b-hosted-stat-lbl">Total Gold</div>
            <div class="b-hosted-stat-val" style="color:#22c55e">{{ number_format($clients->sum('agreed_price')) }}g</div>
        </div>
        <div class="b-hosted-stat">
            <div class="b-hosted-stat-lbl">VIP Clients</div>
            <div class="b-hosted-stat-val" style="color:#f59e0b">{{ $clients->filter(fn($s)=>optional($s->client)->type==='vip')->count() }}</div>
        </div>
        <div class="b-hosted-stat">
            <div class="b-hosted-stat-lbl">Risk Flags</div>
            <div class="b-hosted-stat-val" style="color:#ef4444">{{ $clients->filter(fn($s)=>optional($s->client)->type==='unsafe')->count() }}</div>
        </div>
    </div>

    {{-- client table ── --}}
    @if($clients->count() > 0)
    <div class="b-table-wrap">
        <table class="b-table">
            <thead>
                <tr>
                    <th style="text-align:left">Character</th>
                    <th style="text-align:left">Class / Spec</th>
                    <th style="text-align:left">Security</th>
                    <th style="text-align:left">Advertiser</th>
                    <th style="text-align:right">Gold</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $s)
                @php $st = optional($s->client)->type; @endphp
                <tr>
                    <td>
                        <div style="font-size:12px;font-weight:800;text-transform:uppercase;color:{{ $st==='vip'?'#f59e0b':($st==='unsafe'?'#ef4444':'#e2e8f0') }}">{{ $s->character_name }}</div>
                        @if($s->buyer_realm)<div style="font-size:10px;color:#374151;margin-top:2px">{{ $s->buyer_realm }}</div>@endif
                    </td>
                    <td>
                        <div style="font-weight:800;color:#818cf8;text-transform:uppercase;font-size:11px">{{ $s->class }}</div>
                        <div style="font-size:10px;color:#374151;font-style:italic">{{ $s->spec ?: '—' }}</div>
                    </td>
                    <td>
                        @if($st==='vip')
                            <span class="badge badge-vip">⭐ VIP</span>
                        @elseif($st==='unsafe')
                            <span class="badge badge-risk">⚠ Risk</span>
                        @else
                            <span class="badge badge-safe">✓ Safe</span>
                        @endif
                    </td>
                    <td style="color:#4b5563;font-size:11px;font-weight:700;text-transform:uppercase">
                        {{ optional($s->advertiser)->name ?? '—' }}
                    </td>
                    <td style="text-align:right;font-size:13px;font-weight:800;color:#22c55e;font-family:monospace">
                        {{ number_format($s->agreed_price) }}g
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- cancellation reason --}}
    @if($isCancelled && $raid->cancel_reason)
    <div class="b-reason">
        <div class="b-reason-lbl">Termination Reason</div>
        <div class="b-reason-text">"{{ $raid->cancel_reason }}"</div>
    </div>
    @endif
</div>
