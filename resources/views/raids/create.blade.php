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
    .f-hint{font-size:10px;color:#374151;font-weight:700;margin-top:4px;text-transform:uppercase;letter-spacing:.05em}

    /* timepicker fix */
    .f-input[type="datetime-local"] {
        text-align: left !important;
        color-scheme: dark;
    }
    .f-input[type="datetime-local"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
    }

    /* ── submit button ── */
    .f-submit-wrap{
        background:#111420;border:1px solid #1e2535;border-radius:24px;
        padding:32px;display:flex;flex-direction:column;align-items:center;gap:16px;
        text-align:center;
    }
    .f-submit{
        width:100%;max-width:400px;
        padding:20px;background:linear-gradient(90deg,#6366f1,#4f46e5);
        border:none;border-radius:16px;color:#fff;font-size:13px;font-weight:900;
        text-transform:uppercase;letter-spacing:.2em;cursor:pointer;
        transition:all .3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow:0 10px 30px rgba(99,102,241,.4);
    }
    .f-submit:hover{transform:translateY(-2px);box-shadow:0 15px 40px rgba(99,102,241,.5);filter:brightness(1.1)}
    .f-submit:active{transform:translateY(0)}

    .f-notice{font-size:10px;font-weight:800;color:#374151;text-transform:uppercase;letter-spacing:.1em}
    .f-notice span {color:#ef4444}
</style>

<div class="f-root">
<div class="f-wrap">

    {{-- Header --}}
    <div class="f-topbar">
        <div>
            <h1 class="f-title">Initialize<span>Run</span></h1>
            <p class="f-subtitle">Protocol Deployment · Tier 1 Authorization</p>
        </div>
        <a href="{{ route('dashboard') }}" class="f-back">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Abort & Return
        </a>
    </div>

    <form action="{{ route('raids.manage.store') }}" method="POST">
    @csrf

    {{-- ── TEMPLATE SELECTOR ── --}}
    <div class="f-card">
        <div class="f-card-head">
            <div class="f-card-icon" style="background:rgba(99,102,241,0.1);border:1px solid rgba(99,102,241,0.2)">
                <svg width="20" height="20" fill="none" stroke="#818cf8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
            </div>
            <div class="f-card-label">Strategic Templates</div>
        </div>
        <div class="f-card-body">
            <div class="f-field">
                <label class="f-label">Standard Service Templates</label>
                <select id="service_selector" name="service_id" class="f-select" onchange="applyTemplate()">
                    <option value="">— Manual Override —</option>
                    @foreach($services as $service)
                    <option value="{{ $service->id }}"
                        data-name="{{ $service->name }}"
                        data-category="{{ $service->category }}"
                        data-price="{{ $service->base_price }}"
                        data-boosters="{{ $service->required_boosters }}">
                        {{ $service->name }} ({{ number_format($service->base_price, 0) }}g)
                    </option>
                    @endforeach
                </select>
                <p class="f-hint">Selection will auto-populate telemetry data fields below.</p>
            </div>
        </div>
    </div>

    {{-- ── CORE OBJECTIVES ── --}}
    <div class="f-card">
        <div class="f-card-head">
            <div class="f-card-icon" style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2)">
                <svg width="20" height="20" fill="none" stroke="#22c55e" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
            </div>
            <div class="f-card-label">Core Objectives</div>
        </div>
        <div class="f-card-body">
            <div class="f-field">
                <label class="f-label">Operation Designation</label>
                <input id="title" type="text" name="title" class="f-input" placeholder="e.g. ICC 25 Heroic Full Protocol" required>
                @error('title')<p class="f-error">{{ $message }}</p>@enderror
            </div>

            <div class="f-grid2">
                <div class="f-field">
                    <label class="f-label">Primary Instance</label>
                    <input id="instance_name" type="text" name="instance_name" class="f-input" placeholder="Icecrown Citadel" required>
                </div>
                <div class="f-field">
                    <label class="f-label">Challenge Rating</label>
                    <select id="difficulty" name="difficulty" class="f-select" required>
                        <option value="Normal">Normal</option>
                        <option value="Heroic" selected>Heroic</option>
                        <option value="Mythic">Mythic</option>
                    </select>
                </div>
            </div>

            <div class="f-grid2">
                <div class="f-field">
                    <label class="f-label">Deployment Node (Time)</label>
                    <input type="datetime-local" name="scheduled_at" class="f-input" required>
                    @error('scheduled_at')<p class="f-error">{{ $message }}</p>@enderror
                </div>
                <div class="f-field">
                    <label class="f-label">Ops Window (Hours)</label>
                    <input type="number" name="duration_hours" value="3" min="1" max="12" class="f-input" required>
                </div>
            </div>

            <div class="f-grid2">
                <div class="f-field">
                    <label class="f-label">Service Cluster</label>
                    <select id="service_category" name="service_category" class="f-select" required>
                        @foreach(['Raid','Mythic+','PvP','Leveling','Gold Track','Delves'] as $cat)
                        <option value="{{ $cat }}" {{ $cat=='Raid' ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="f-field">
                    <label class="f-label">Regional Hub</label>
                    <select name="region" class="f-select" required>
                        <option value="EU" selected>European (EU)</option>
                        <option value="NA">North American (NA)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- ── CAPACITY & VALUATION ── --}}
    <div class="f-card">
        <div class="f-card-head">
            <div class="f-card-icon" style="background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.2)">
                <svg width="20" height="20" fill="none" stroke="#f59e0b" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="f-card-label">Capacity & Valuation</div>
        </div>
        <div class="f-card-body">
            <div class="f-grid2">
                <div class="f-field">
                    <label class="f-label">Unit Capacity (Max Players)</label>
                    <input id="max_players" type="number" name="max_players" value="25" min="1" max="40" class="f-input" required>
                </div>
                <div class="f-field">
                    <label class="f-label">Reward per Unit (Price G)</label>
                    <input id="price_per_spot" type="number" name="price_per_spot" value="0" min="0" step="1" class="f-input" required>
                </div>
            </div>

            <div class="f-grid2">
                <div class="f-field">
                    <label class="f-label">Armor Slots: Cloth</label>
                    <input type="number" name="cloth_spots" value="0" min="0" class="f-input">
                </div>
                <div class="f-field">
                    <label class="f-label">Armor Slots: Leather</label>
                    <input type="number" name="leather_spots" value="0" min="0" class="f-input">
                </div>
            </div>
            <div class="f-grid2">
                <div class="f-field">
                    <label class="f-label">Armor Slots: Mail</label>
                    <input type="number" name="mail_spots" value="0" min="0" class="f-input">
                </div>
                <div class="f-field">
                    <label class="f-label">Armor Slots: Plate</label>
                    <input type="number" name="plate_spots" value="0" min="0" class="f-input">
                </div>
            </div>
        </div>
    </div>

    {{-- ── BRIEFING ── --}}
    <div class="f-card">
        <div class="f-card-head">
            <div class="f-card-icon" style="background:rgba(107,114,128,0.1);border:1px solid rgba(107,114,128,0.2)">
                <svg width="20" height="20" fill="none" stroke="#9ca3af" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div class="f-card-label">Intelligence Briefing</div>
        </div>
        <div class="f-card-body">
            <div class="f-field">
                <label class="f-label">Mission Brief & Loot Rules</label>
                <textarea name="description" class="f-textarea" placeholder="Specify loot distribution, specific requirements, or tactical notes..."></textarea>
            </div>
        </div>
    </div>

    <div class="f-submit-wrap">
        <button type="submit" class="f-submit">Authorize Protocol & Sync</button>
        <p class="f-notice"><span>⚠</span> MISSION REQUIRES HIGH-COMMAND VERIFICATION BEFORE BROADCAST</p>
    </div>

    </form>
</div>
</div>

<script>
function applyTemplate() {
    const sel = document.getElementById('service_selector');
    const opt = sel.options[sel.selectedIndex];
    if (!opt.value) return;
    document.getElementById('title').value          = opt.dataset.name;
    document.getElementById('service_category').value = opt.dataset.category;
    document.getElementById('price_per_spot').value = opt.dataset.price;
    document.getElementById('max_players').value    = parseInt(opt.dataset.boosters || 0) + 2;
}
</script>
</x-app-layout>
