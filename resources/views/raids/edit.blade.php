<x-app-layout>
<style>
    [x-cloak]{display:none!important}
    .f-root{background:#08090d;min-height:100vh;padding:60px 24px;color:#94a3b8}
    .f-wrap{max-width:860px;margin:0 auto}

    /* ── premium header ── */
    .f-topbar{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:48px}
    .f-title{font-size:36px;font-weight:900;color:#fff;letter-spacing:-1.5px;text-transform:uppercase;line-height:.9}
    .f-title span{color:#6366f1}
    .f-subtitle{font-size:10px;font-weight:800;color:#374151;text-transform:uppercase;letter-spacing:.25em;margin-top:12px}
    .f-back{
        display:inline-flex;align-items:center;gap:8px;
        padding:10px 18px;background:#161b27;border:1px solid #1e2535;
        border-radius:12px;color:#6b7280;font-size:10px;font-weight:800;
        text-transform:uppercase;letter-spacing:.1em;text-decoration:none;
        transition:all .2s;
    }
    .f-back:hover{color:#fff;border-color:#374151;background:#1c2331}

    /* ── glass card ── */
    .f-card{
        background:linear-gradient(165deg,#0f1117,#111420);
        border:1px solid #1e2535;border-radius:24px;
        overflow:hidden;margin-bottom:24px;
        box-shadow:0 20px 50px rgba(0,0,0,.3);
    }
    .f-card-head{
        padding:24px 32px;border-bottom:1px solid #1e2535;
        display:flex;align-items:center;gap:14px;
        background:rgba(255,255,255,.01);
    }
    .f-card-icon{
        width:40px;height:40px;border-radius:12px;
        display:flex;align-items:center;justify-content:center;
        box-shadow:0 8px 16px rgba(0,0,0,.2);
    }
    .f-card-label{font-size:12px;font-weight:900;color:#fff;text-transform:uppercase;letter-spacing:.15em}
    .f-card-body{padding:32px;display:flex;flex-direction:column;gap:24px}

    /* ── form elements ── */
    .f-grid2{display:grid;grid-template-columns:1fr 1fr;gap:20px}
    .f-grid3{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
    .f-grid4{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
    @media(max-width:640px){.f-grid2,.f-grid3,.f-grid4{grid-template-columns:1fr}}

    .f-field{display:flex;flex-direction:column;gap:8px}
    .f-label{font-size:11px;font-weight:800;color:#4b5563;text-transform:uppercase;letter-spacing:.12em}
    .f-input,.f-select,.f-textarea{
        background:#0a0c12;border:1px solid #1e2535;border-radius:14px;
        color:#fff;font-size:14px;font-weight:600;
        padding:14px 18px;width:100%;box-sizing:border-box;
        transition:all .2s;box-shadow:inset 0 2px 4px rgba(0,0,0,.1);
    }
    .f-input:focus,.f-select:focus,.f-textarea:focus{
        border-color:#6366f1;outline:none;background:#0d111a;
        box-shadow:0 0 0 4px rgba(99,102,241,.1),inset 0 2px 4px rgba(0,0,0,.1);
    }
    .f-textarea{resize:vertical;min-height:120px;line-height:1.6}
    
    .f-error{font-size:10px;font-weight:800;color:#ef4444;text-transform:uppercase;letter-spacing:.05em;margin-top:6px}

    /* timepicker fix */
    .f-input[type="datetime-local"] {
        text-align: left !important;
        color-scheme: dark;
    }
    .f-input[type="datetime-local"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
    }

    /* ── roster table ── */
    .r-table{width:100%;border-collapse:collapse}
    .r-table th{padding:12px 24px;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.12em;color:#374151;background:#0a0c12;border-bottom:1px solid #1e2535;text-align:left}
    .r-table td{padding:18px 24px;font-size:13px;font-weight:600;color:#94a3b8;border-bottom:1px solid #0f1117}
    .r-table tr:hover td{background:#ffffff03}
    .r-table tr:last-child td{border-bottom:none}
    
    .r-select{
        background:#161b27;border:1px solid #1e2535;border-radius:10px;
        color:#fff;font-size:11px;font-weight:800;padding:8px 12px;
        text-transform:uppercase;letter-spacing:.05em;cursor:pointer;
        transition:all .15s;
    }
    .r-select:focus{border-color:#6366f1;outline:none}

    /* ── submit button ── */
    .f-submit{
        width:100%;max-width:400px;margin:0 auto;
        padding:20px;background:linear-gradient(90deg,#6366f1,#4f46e5);
        border:none;border-radius:16px;color:#fff;font-size:13px;font-weight:900;
        text-transform:uppercase;letter-spacing:.2em;cursor:pointer;
        transition:all .3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow:0 10px 30px rgba(99,102,241,.4);
        display:block;
    }
    .f-submit:hover{transform:translateY(-2px);box-shadow:0 15px 40px rgba(99,102,241,.5);filter:brightness(1.1)}

    .f-status-notice{
        background:#f59e0b15;border:1px solid #f59e0b30;border-radius:16px;
        padding:16px 24px;font-size:11px;font-weight:800;color:#f59e0b;
        text-transform:uppercase;letter-spacing:.1em;text-align:center;margin-top:24px;
    }
</style>

<div class="f-root">
<div class="f-wrap">

    {{-- Header --}}
    <div class="f-topbar">
        <div>
            <h1 class="f-title">Edit<span>Protocol</span></h1>
            <p class="f-subtitle">Modification Mode · ID: {{ $raid->id }}</p>
        </div>
        <a href="{{ route('dashboard') }}" class="f-back">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Abort Modification
        </a>
    </div>

    <form action="{{ route('raids.manage.update', $raid->id) }}" method="POST">
    @csrf @method('PUT')

    {{-- ── MISSION DETAILS ── --}}
    <div class="f-card">
        <div class="f-card-head">
            <div class="f-card-icon" style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2)">
                <svg width="20" height="20" fill="none" stroke="#22c55e" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
            </div>
            <div class="f-card-label">Mission Briefing</div>
        </div>
        <div class="f-card-body">
            <div class="f-field">
                <label class="f-label">Operation Designation</label>
                <input type="text" name="title" value="{{ old('title', $raid->title) }}" class="f-input" required>
                @error('title')<p class="f-error">{{ $message }}</p>@enderror
            </div>

            <div class="f-grid2">
                <div class="f-field">
                    <label class="f-label">Primary Instance</label>
                    <input type="text" name="instance_name" value="{{ old('instance_name', $raid->instance_name) }}" class="f-input" required>
                </div>
                <div class="f-field">
                    <label class="f-label">Challenge Rating</label>
                    <select name="difficulty" class="f-select" required>
                        @foreach(['Normal','Heroic','Mythic'] as $d)
                        <option value="{{ $d }}" {{ $raid->difficulty === $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="f-grid2">
                <div class="f-field">
                    <label class="f-label">Deployment Node</label>
                    <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', $raid->scheduled_at->format('Y-m-d\TH:i')) }}" class="f-input" required>
                </div>
                <div class="f-field">
                    <label class="f-label">Ops Window (Hours)</label>
                    <input type="number" name="duration_hours" value="{{ old('duration_hours', $raid->duration_hours) }}" min="1" max="12" class="f-input" required>
                </div>
            </div>

            <div class="f-grid2">
                <div class="f-field">
                    <label class="f-label">Service Cluster</label>
                    <select name="service_category" class="f-select" required>
                        @foreach(['Raid','Mythic+','PvP','Leveling','Gold Track','Delves'] as $cat)
                        <option value="{{ $cat }}" {{ $raid->service_category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="f-field">
                    <label class="f-label">Regional Hub</label>
                    <select name="region" class="f-select" required>
                        <option value="EU" {{ $raid->region === 'EU' ? 'selected' : '' }}>EU — European Union</option>
                        <option value="NA" {{ $raid->region === 'NA' ? 'selected' : '' }}>NA — North America</option>
                    </select>
                </div>
            </div>
            
            <div class="f-field">
                <label class="f-label">Description & Tactical Rules</label>
                <textarea name="description" class="f-textarea">{{ old('description', $raid->description) }}</textarea>
            </div>
        </div>
    </div>

    {{-- ── CAPACITY & VALUATION ── --}}
    <div class="f-card">
        <div class="f-card-head">
            <div class="f-card-icon" style="background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.2)">
                <svg width="20" height="20" fill="none" stroke="#f59e0b" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="f-card-label">Capacity & Pricing</div>
        </div>
        <div class="f-card-body">
            <div class="f-grid2">
                <div class="f-field">
                    <label class="f-label">Max Players</label>
                    <input type="number" name="max_players" value="{{ old('max_players', $raid->max_players) }}" min="1" max="40" class="f-input" required>
                </div>
                <div class="f-field">
                    <label class="f-label">Reward per Unit (G)</label>
                    <input type="number" name="price_per_spot" value="{{ old('price_per_spot', $raid->price_per_spot) }}" min="0" step="0.01" class="f-input">
                </div>
            </div>
            
            <div class="f-grid4">
                @foreach(['cloth'=>'Cloth','leather'=>'Leather','mail'=>'Mail','plate'=>'Plate'] as $key=>$lbl)
                <div class="f-field">
                    <label class="f-label" style="font-size:9px">{{ $lbl }}</label>
                    <input type="number" name="{{ $key }}_spots" value="{{ old($key.'_spots', $raid->{$key.'_spots'}) }}" min="0" class="f-input">
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <button type="submit" class="f-submit">Synchronize Protocol Changes</button>
    </form>

    {{-- ── ROSTER MANAGEMENT ── --}}
    @if($raid->signups->count() > 0)
    <div class="f-card" style="margin-top:24px">
        <div class="f-card-head">
            <div class="f-card-icon" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2)">
                <svg width="20" height="20" fill="none" stroke="#f87171" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div class="f-card-label">Operational Roster ({{ $raid->signups->count() }} detected)</div>
        </div>
        <div style="overflow-x:auto">
            <table class="r-table">
                <thead>
                    <tr>
                        <th>Operator</th>
                        <th>Classification</th>
                        <th>Asset Value</th>
                        <th style="text-align:right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($raid->signups as $signup)
                    <tr>
                        <td>
                            <div style="font-size:13px;font-weight:900;color:#fff;text-transform:uppercase;letter-spacing:-.2px">{{ $signup->character_name }}</div>
                            <div style="font-size:10px;color:#374151;font-weight:700;text-transform:uppercase;margin-top:2px">{{ $signup->client_discord ?? 'COMM_SILENT' }}</div>
                        </td>
                        <td>
                            <div style="font-size:11px;font-weight:800;color:#6366f1;text-transform:uppercase;letter-spacing:.05em">{{ $signup->class }}</div>
                            <div style="font-size:9px;color:#374151;text-transform:uppercase;font-weight:700">{{ $signup->role }}</div>
                        </td>
                        <td style="font-family:monospace;font-weight:900;color:#22c55e">{{ number_format($signup->agreed_price) }}g</td>
                        <td style="text-align:right">
                            <form action="{{ route('signups.updateStatus', $signup->id) }}" method="POST">
                                @csrf
                                <select name="status" onchange="this.form.submit()" class="r-select">
                                    @foreach(['waitlist'=>'Waitlist','accepted'=>'Accepted','rejected'=>'Rejected','cancelled'=>'Cancelled'] as $val=>$lbl)
                                    <option value="{{ $val }}" {{ $signup->status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="f-status-notice">
        Protocol Status: <strong style="color:{{ $raid->status === 'approved' ? '#22c55e' : '#f59e0b' }}">{{ strtoupper($raid->status) }}</strong>
        — Major modifications to approved protocols may trigger re-verification.
    </div>

</div>
</div>
</x-app-layout>
