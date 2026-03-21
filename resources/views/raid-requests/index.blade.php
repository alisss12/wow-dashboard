@extends('layouts.app')

@section('content')
<style>
    .rr-root { max-width: 1100px; margin: 0 auto; padding: 32px 20px; }
    .rr-heading { font-size: 26px; font-weight: 900; color: #fff; text-transform: uppercase; letter-spacing: .08em; font-style: italic; margin-bottom: 4px; }
    .rr-sub { font-size: 11px; color: #4b5563; font-weight: 700; text-transform: uppercase; letter-spacing: .12em; margin-bottom: 32px; }
    .rr-card { background: #0f1117; border: 1px solid #1e2535; border-radius: 24px; overflow: hidden; margin-bottom: 20px; }
    .rr-card-header { padding: 20px 24px; background: #0a0c14; border-bottom: 1px solid #1e2535; display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; }
    .rr-card-body { padding: 20px 24px; }
    .rr-label { font-size: 9px; font-weight: 900; color: #374151; text-transform: uppercase; letter-spacing: .12em; margin-bottom: 3px; }
    .rr-value { font-size: 13px; font-weight: 800; color: #fff; }
    .rr-badge { padding: 4px 12px; border-radius: 8px; font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: .1em; }
    .rr-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; margin-bottom: 16px; }
    .rr-conditions { background: #1e2535; border-radius: 10px; padding: 12px 16px; font-size: 12px; color: #9ca3af; font-style: italic; line-height: 1.5; margin-bottom: 16px; }
    .rr-btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 10px; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: .08em; border: none; cursor: pointer; transition: all .2s; }
    .rr-btn-primary { background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; }
    .rr-btn-primary:hover { background: linear-gradient(135deg, #818cf8, #6366f1); }
    .rr-btn-applied { background: #22c55e20; color: #22c55e; border: 1px solid #22c55e40; cursor: default; }
    .rr-empty { text-align: center; padding: 80px 32px; }
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.8); z-index: 1000; display: flex; align-items: center; justify-content: center; }
    .modal-box { background: #0f1117; border: 1px solid #1e2535; border-radius: 24px; padding: 32px; width: 100%; max-width: 500px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); }
    .rr-form-input { width: 100%; background: #0a0c14; border: 1px solid #1e2535; border-radius: 10px; padding: 12px 14px; color: #fff; font-size: 13px; outline: none; transition: border-color .2s; box-sizing: border-box; }
    .rr-form-input:focus { border-color: #6366f1; }
    .deadline-warn { font-size: 10px; color: #ef4444; font-weight: 700; margin-top: 8px; }
</style>

<div class="rr-root" x-data="{ applyModal: null }">

    <div class="rr-heading">Open Raid Requests</div>
    <div class="rr-sub">Apply to run the raid for an advertiser's client — Admin will select one team</div>

    @foreach(['success'=>'22c55e','error'=>'ef4444','status'=>'6366f1'] as $k=>$c)
    @if(session($k))
    <div style="background:#{{ $c }}15;border:1px solid #{{ $c }}30;color:#{{ $c }};padding:14px 20px;border-radius:12px;font-size:12px;font-weight:700;margin-bottom:24px">
        {{ session($k) }}
    </div>
    @endif
    @endforeach

    @forelse($openRequests as $req)
    @php
        $alreadyApplied = in_array($req->id, $myApplicationIds);
        $appCount       = $req->signups->count();
        $isExpired      = $req->applications_close_at && now()->isAfter($req->applications_close_at);
    @endphp
    <div class="rr-card">
        <div class="rr-card-header">
            <div>
                <div style="font-size:10px;font-weight:900;color:#6366f1;text-transform:uppercase;letter-spacing:.12em;margin-bottom:6px">
                    Request #{{ $req->id }} &nbsp;·&nbsp; {{ $req->difficulty }} {{ $req->instance_name }}
                </div>
                <div style="font-size:20px;font-weight:900;color:#fff;text-transform:uppercase;font-style:italic">{{ $req->title }}</div>
                <div style="font-size:10px;color:#4b5563;margin-top:4px">
                    Requested by <span style="color:#9ca3af;font-weight:700">{{ $req->requestedBy?->name ?? 'Advertiser' }}</span>
                </div>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px">
                <span class="rr-badge" style="background:#6366f120;color:#818cf8;border:1px solid #6366f140">
                    {{ $appCount }} Applicant{{ $appCount !== 1 ? 's' : '' }}
                </span>
                @if($isExpired)
                <span class="rr-badge" style="background:#ef444420;color:#ef4444;border:1px solid #ef444440">Applications Closed</span>
                @elseif($alreadyApplied)
                <span class="rr-badge" style="background:#22c55e20;color:#22c55e;border:1px solid #22c55e40">✓ Applied</span>
                @else
                <span class="rr-badge" style="background:#f59e0b20;color:#f59e0b;border:1px solid #f59e0b40">Open</span>
                @endif
            </div>
        </div>

        <div class="rr-card-body">
            <div class="rr-grid">
                <div>
                    <div class="rr-label">Preferred Start Time</div>
                    <div class="rr-value">{{ $req->preferred_start_time?->format('D, M j — H:i') ?? $req->scheduled_at->format('D, M j — H:i') }}</div>
                </div>
                <div>
                    <div class="rr-label">Client Slots</div>
                    <div class="rr-value">{{ $req->max_players > 5 ? $req->max_players - 5 : $req->max_players }}</div>
                </div>
                <div>
                    <div class="rr-label">Armor Stack</div>
                    <div class="rr-value">{{ $req->armor_stack ?: '—' }}</div>
                </div>
                <div>
                    <div class="rr-label">Difficulty</div>
                    <div class="rr-value">{{ $req->difficulty }}</div>
                </div>
                @if($req->applications_close_at)
                <div>
                    <div class="rr-label">Deadline</div>
                    <div class="rr-value" style="color:{{ $isExpired ? '#ef4444' : '#f59e0b' }}">{{ $req->applications_close_at->format('M j, H:i') }}</div>
                </div>
                @endif
            </div>

            @if($req->special_conditions)
            <div class="rr-label" style="margin-bottom:6px">Special Conditions</div>
            <div class="rr-conditions">"{{ $req->special_conditions }}"</div>
            @endif

            @if(!$alreadyApplied && !$isExpired)
            <button @click="applyModal = {{ $req->id }}" class="rr-btn rr-btn-primary">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Apply for This Run
            </button>
            @elseif($alreadyApplied)
            <span class="rr-btn rr-btn-applied">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                Application Submitted
            </span>
            @endif
        </div>
    </div>

    {{-- Apply Modal --}}
    <div class="modal-overlay" x-show="applyModal === {{ $req->id }}" x-cloak @click.self="applyModal = null">
        <div class="modal-box">
            <div style="font-size:14px;font-weight:900;color:#fff;text-transform:uppercase;margin-bottom:4px">Apply for Raid #{{ $req->id }}</div>
            <div style="font-size:11px;color:#4b5563;margin-bottom:24px">{{ $req->title }}</div>

            <form action="{{ route('raid-requests.apply', $req->id) }}" method="POST">
                @csrf
                <div style="margin-bottom:16px">
                    <label class="rr-label">Your Conditions / Notes</label>
                    <textarea name="notes"
                        class="rr-form-input"
                        rows="4"
                        placeholder="e.g. 'Can bring 10 players', 'Need 2 client DPS slots', 'Available from 18:30 UTC'"
                        style="margin-top:6px;resize:vertical"></textarea>
                </div>
                <div style="display:flex;gap:12px">
                    <button type="submit" class="rr-btn rr-btn-primary" style="flex:1;justify-content:center">
                        Submit Application
                    </button>
                    <button type="button" @click="applyModal = null" class="rr-btn" style="background:#1e2535;color:#9ca3af;flex:1;justify-content:center">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    @empty
    <div class="rr-card">
        <div class="rr-empty">
            <div style="font-size:32px;margin-bottom:16px">📭</div>
            <div style="font-size:14px;font-weight:900;color:#374151;text-transform:uppercase;letter-spacing:.1em;margin-bottom:8px">No Open Requests</div>
            <div style="font-size:12px;color:#1f2937">Admin hasn't published any raid requests yet. Check back soon.</div>
        </div>
    </div>
    @endforelse

</div>
@endsection
