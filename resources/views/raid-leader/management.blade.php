<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
        .dashboard-dark { background-color: #0c0e14; color: #94a3b8; }
        .card-dark { background-color: #111827; border: 1px solid #1f2937; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .btn-action { @apply px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition shadow-lg; }
    </style>

    <div class="min-h-screen dashboard-dark pb-12">
        <div class="bg-gray-950/80 backdrop-blur-xl border-b border-gray-800/50 sticky top-0 z-30 shadow-2xl">
            <div class="max-w-7xl mx-auto px-6 lg:px-12">
                <div class="flex justify-between items-center h-24">
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('dashboard') }}?tab={{ request('tab', 'my_runs') }}" class="group bg-gray-900 border border-gray-800 p-3 rounded-2xl hover:border-indigo-500/50 transition-all duration-500">
                            <svg class="w-5 h-5 text-gray-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                        <div class="flex flex-col">
                            <h2 class="text-white font-black text-2xl tracking-tighter uppercase flex items-center italic">
                                MISSION<span class="text-indigo-600 ml-1">CONTROL</span>
                            </h2>
                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mt-0.5 opacity-80">Protocol #{{ $raid->id }} • {{ $raid->title }}</span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-10">
                    @php
                        $statusColors = [
                            'draft' => 'bg-gray-900 border-gray-800 text-gray-600',
                            'published' => 'bg-green-600/10 border-green-500/20 text-green-400',
                            'full' => 'bg-blue-600/10 border-blue-500/20 text-blue-400',
                            'running' => 'bg-indigo-600/10 border-indigo-500/20 text-indigo-400',
                            'completed' => 'bg-purple-600/10 border-purple-500/20 text-purple-400',
                            'cancelled' => 'bg-red-600/10 border-red-500/20 text-red-500',
                            'expired' => 'bg-orange-600/10 border-orange-500/20 text-orange-400',
                        ];
                        $badgeClass = $statusColors[$raid->status] ?? 'bg-indigo-600/10 border-indigo-500/20 text-indigo-400';
                    @endphp
                    <div class="flex flex-col items-center">
                        <span class="text-[8px] font-black text-gray-600 uppercase mb-1.5 tracking-widest">Protocol Status</span>
                        <span class="px-5 py-1.5 border rounded-2xl text-[10px] font-black uppercase tracking-widest {{ $badgeClass }} shadow-lg">{{ $raid->status }}</span>
                    </div>
                        <div class="h-10 w-px bg-gray-800/50"></div>
                        <div class="flex flex-col items-end">
                            <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Deployment Time</span>
                            <span class="text-sm font-black text-white italic tracking-tight">{{ $raid->scheduled_at->format('H:i') }} <span class="text-[10px] text-gray-600 uppercase ml-1">UTC</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-12 mt-12 pb-24">
            @if(session('status'))
                <div class="bg-indigo-600/10 border-l-4 border-indigo-600 text-indigo-400 px-8 py-5 rounded-2xl mb-10 font-black text-xs uppercase tracking-widest animate-in fade-in slide-in-from-top-4 duration-500">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Transmission: {{ session('status') }}
                    </span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">
                <!-- MISSION COMMANDS -->
                <div class="lg:col-span-1 space-y-8">
                    <div class="card-dark p-8 rounded-[2.5rem] bg-gradient-to-br from-gray-900 to-indigo-950/10 border-gray-800/40 shadow-2xl">
                        <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-10 pb-6 border-b border-gray-800 flex items-center">
                            <svg class="w-4 h-4 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                            Directives
                        </h3>
                        <div class="space-y-6">
                            @if(in_array($raid->status, ['approved', 'published']))
                                <form action="{{ route('raid-management.lock', $raid->id) }}" method="POST">
                                    @csrf
                                    <button class="w-full bg-indigo-600 hover:bg-indigo-500 text-white px-8 py-5 rounded-3xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 shadow-[0_0_20px_rgba(79,70,229,0.3)] hover:scale-[1.02] active:scale-95">Lock Deployment</button>
                                </form>
                            @endif

                            @if(in_array($raid->status, ['approved', 'published', 'roster_locked', 'ready']))
                                <form action="{{ route('raid-management.start', $raid->id) }}" method="POST">
                                    @csrf
                                    <button class="w-full bg-green-600 hover:bg-green-500 text-white px-8 py-5 rounded-3xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 shadow-[0_0_20px_rgba(22,163,74,0.3)] hover:scale-[1.02] active:scale-95">Initiate Mission</button>
                                </form>
                            @endif

                            @if($raid->status === 'running')
                                <form action="{{ route('raid-management.complete', $raid->id) }}" method="POST">
                                    @csrf
                                    <button class="w-full bg-indigo-600 hover:bg-indigo-500 text-white px-8 py-5 rounded-3xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 shadow-[0_0_20px_rgba(79,70,229,0.3)] hover:scale-[1.02] active:scale-95">Finalize protocol</button>
                                </form>
                            @endif

                            <button @click="$dispatch('open-report-modal')" class="w-full bg-red-950/20 border border-red-900/30 text-red-500 px-8 py-5 rounded-3xl text-[10px] font-black uppercase tracking-widest transition-all hover:bg-red-900/40">Signal Distress</button>
                        </div>
                    </div>

                    <div class="card-dark p-8 rounded-[2.5rem] bg-gray-900/40 border-gray-800/40">
                        <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-8 flex items-center">
                            <svg class="w-4 h-4 mr-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            Asset Valuation
                        </h4>
                        <div class="space-y-6">
                            <div class="flex justify-between items-end">
                                <span class="text-[10px] font-bold text-gray-600 uppercase tracking-tighter">House Reserve:</span>
                                <span class="text-xs font-black text-white uppercase">{{ $raid->management_cut_percentage ?: 10 }}%</span>
                            </div>
                            <div class="h-px bg-gray-800/50"></div>
                            <div class="flex justify-between items-end">
                                <span class="text-[10px] font-bold text-gray-600 uppercase tracking-tighter">Net Protocol Value:</span>
                                <span class="text-xl font-black text-green-500 font-mono tracking-tighter">{{ number_format($raid->signups->where('is_booster', false)->sum('agreed_price')) }}g</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ROSTER / CLIENT PAYLOAD -->
                <div class="lg:col-span-3 space-y-10">
                    <!-- Protocol Applications (Pending Boosters) — Read-Only -->
                    @if($raid->signups->where('is_booster', true)->where('status', 'pending')->count() > 0)
                    <div class="card-dark rounded-[3rem] overflow-hidden border-t-4 border-yellow-600/50 shadow-2xl bg-gray-900/20">
                        <div class="px-10 py-8 bg-gray-900/80 border-b border-gray-800/50 flex justify-between items-center">
                            <div>
                                <h4 class="text-xs font-black text-yellow-500 uppercase tracking-[0.2em] italic">Station Standby — Applications</h4>
                                <p class="text-[9px] text-gray-600 font-bold mt-1 uppercase tracking-wider">Admin will select and notify applicants via the Command Center</p>
                            </div>
                            <span class="bg-yellow-600/10 text-yellow-500 px-3 py-1 rounded-full text-[8px] font-black uppercase border border-yellow-500/20 animate-pulse">{{ $raid->signups->where('is_booster', true)->where('status', 'pending')->count() }} Pending</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-900/30 text-[9px] font-black text-gray-700 uppercase">
                                    <tr>
                                        <th class="px-10 py-5">Applicant</th>
                                        <th class="px-10 py-5">Role / Class</th>
                                        <th class="px-10 py-5">Conditions</th>
                                        <th class="px-10 py-5">Applied</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-800/50">
                                    @foreach($raid->signups->where('is_booster', true)->where('status', 'pending') as $app)
                                        <tr class="hover:bg-yellow-600/5 transition-all duration-300">
                                            <td class="px-10 py-6">
                                                <div class="flex items-center space-x-4">
                                                    <div class="w-10 h-10 rounded-2xl bg-yellow-600/10 border border-yellow-500/20 flex items-center justify-center">
                                                        <span class="text-xs font-black text-yellow-400">{{ substr($app->character_name, 0, 1) }}</span>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-sm font-black text-gray-200 uppercase tracking-tight">{{ $app->character_name }}</span>
                                                        <span class="text-[8px] font-black text-gray-600 tracking-widest">{{ $app->user->name ?? '—' }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-10 py-6">
                                                <div class="flex flex-col">
                                                    <span class="text-[10px] font-black text-white uppercase tracking-widest">{{ $app->role }}</span>
                                                    <span class="text-[8px] font-bold text-gray-600 uppercase">{{ $app->class }}</span>
                                                </div>
                                            </td>
                                            <td class="px-10 py-6">
                                                <span class="text-[10px] text-gray-400 italic">{{ $app->notes ?: '—' }}</span>
                                            </td>
                                            <td class="px-10 py-6 text-[9px] font-black text-gray-600 uppercase tracking-tighter">
                                                {{ $app->created_at->diffForHumans() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-10 py-4 bg-gray-950/50 border-t border-gray-800/40">
                            <span class="text-[9px] font-black text-gray-700 uppercase tracking-[0.2em]">⚙ Admin selects via Command Center → /admin/raid-signups</span>
                        </div>
                    </div>
                    @endif

                    <!-- Boosters (The Squad) -->
                    <div class="card-dark rounded-[3rem] overflow-hidden border-gray-800/50 shadow-2xl bg-gray-900/20">
                        <div class="px-10 py-8 bg-gray-900/80 border-b border-gray-800/50 flex justify-between items-center">
                            <div class="flex items-center space-x-4">
                                <h4 class="text-xs font-black text-white uppercase tracking-[0.2em] italic">Unit Deployment</h4>
                                <span class="bg-indigo-600/10 text-indigo-400 px-3 py-1 rounded-full text-[8px] font-black uppercase border border-indigo-500/20">{{ $raid->signups->where('is_booster', true)->where('status', 'accepted')->count() }} active assets</span>
                            </div>
                            <button @click="$dispatch('open-add-booster-modal')" class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-3 rounded-2xl text-[9px] font-black uppercase tracking-widest transition shadow-lg shadow-indigo-600/30 flex items-center">
                                <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Deploy Extra Asset
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-900/30 text-[9px] font-black text-gray-700 uppercase">
                                    <tr>
                                        <th class="px-10 py-5">Booster Unit</th>
                                        <th class="px-10 py-5">Assigned Role</th>
                                        <th class="px-10 py-5">Deployment Status</th>
                                        <th class="px-10 py-5">Attendance</th>
                                        <th class="px-10 py-5 text-right">Operations</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-800/50">
                                    @forelse($raid->signups->where('is_booster', true)->where('status', 'accepted') as $booster)
                                        <tr class="hover:bg-indigo-600/5 transition-all duration-300">
                                            <td class="px-10 py-6">
                                                <div class="flex items-center space-x-4">
                                                    <div class="w-10 h-10 rounded-2xl bg-indigo-600/10 border border-indigo-500/20 flex items-center justify-center">
                                                        <span class="text-xs font-black text-indigo-400">{{ substr($booster->character_name, 0, 1) }}</span>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-sm font-black text-gray-200 uppercase tracking-tight">{{ $booster->character_name }}</span>
                                                        <span class="text-[8px] font-black text-indigo-500 tracking-widest">VERIFIED ASSET</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-10 py-6">
                                                <span class="text-[10px] font-black text-white uppercase tracking-widest bg-gray-800 px-3 py-1 rounded-xl border border-gray-700">{{ $booster->role }}</span>
                                            </td>
                                            <td class="px-10 py-6 text-[10px] font-black text-gray-500 uppercase tracking-tighter">Combat Ready</td>
                                            <td class="px-10 py-6">
                                                <form action="{{ route('raid-management.toggle-attendance', [$raid->id, $booster->id]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="px-4 py-1.5 rounded-2xl text-[8px] font-black uppercase tracking-widest transition-all duration-300 hover:scale-105 active:scale-95 {{ $booster->attendance_status === 'present' ? 'bg-green-600/10 text-green-500 border border-green-500/20' : 'bg-red-600/10 text-red-500 border border-red-500/20' }}">
                                                        {{ $booster->attendance_status }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="px-10 py-6 text-right">
                                                <form action="{{ route('raid-management.remove-booster', [$raid->id, $booster->id]) }}" method="POST" onsubmit="return confirm('Abort deployment for this booster asset?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500/60 hover:text-red-500 text-[9px] font-black uppercase tracking-[0.2em] transition hover:scale-110 active:scale-90">Decommission</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="p-20 text-center text-[10px] text-gray-700 font-black uppercase italic tracking-[0.5em]">Zero Assets Deployed</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Clients (The Payload) -->
                    <div class="card-dark rounded-[3rem] overflow-hidden border-t-4 border-green-600/30 shadow-2xl bg-gray-900/20">
                         <div class="px-10 py-8 bg-gray-900/80 border-b border-gray-800/50">
                            <h4 class="text-xs font-black text-green-500 uppercase tracking-[0.2em] italic">Client Payload Assets</h4>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-900/30 text-[9px] font-black text-gray-700 uppercase">
                                    <tr>
                                        <th class="px-10 py-5">Asset Signature</th>
                                        <th class="px-10 py-5">Security Status</th>
                                        <th class="px-10 py-5">Agent</th>
                                        <th class="px-10 py-5">Comm Rate</th>
                                        <th class="px-10 py-5 text-right">Value</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-800/50">
                                    @forelse($raid->signups->where('is_booster', false) as $client)
                                        <tr class="hover:bg-green-600/5 transition-all duration-300">
                                            <td class="px-10 py-6">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-black @if($client->client?->type === 'vip') text-yellow-500 @elseif($client->client?->type === 'unsafe') text-red-500 @else text-gray-200 @endif uppercase tracking-tight">{{ $client->character_name }}</span>
                                                    <span class="text-[9px] font-black text-gray-600 uppercase tracking-[0.2em]">{{ $client->buyer_realm }}</span>
                                                </div>
                                            </td>
                                            <td class="px-10 py-6">
                                                @if($client->client && $client->client->type === 'vip')
                                                    <span class="inline-flex items-center px-4 py-2 bg-yellow-600/10 text-yellow-500 rounded-2xl text-[8px] font-black uppercase tracking-widest border border-yellow-500/20 italic shadow-lg shadow-yellow-500/5">
                                                        <svg class="w-2.5 h-2.5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                        VIP Protocol
                                                    </span>
                                                @elseif($client->client && $client->client->type === 'unsafe')
                                                    <span class="inline-flex items-center px-4 py-2 bg-red-600/10 text-red-500 rounded-2xl text-[8px] font-black uppercase tracking-widest border border-red-500/20 italic">
                                                        <svg class="w-2.5 h-2.5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                                        High Risk Flag
                                                    </span>
                                                    <span class="text-[7px] text-red-800 font-bold block mt-1 ml-1 uppercase">SYNC DEPOSIT ONLY</span>
                                                @else
                                                    <span class="inline-flex items-center px-4 py-2 bg-green-600/10 text-green-500 rounded-2xl text-[8px] font-black uppercase tracking-widest border border-green-500/20">
                                                        <svg class="w-2.5 h-2.5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                        Safe Vector
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-10 py-6 text-[10px] font-black text-gray-500 uppercase tracking-widest">{{ $client->advertiser->name ?? 'System' }}</td>
                                            <td class="px-10 py-6 text-[11px] font-black text-indigo-400 font-mono italic">
                                                {{ $client->ad_cut > 0 ? number_format($client->ad_cut) : '10%' }}
                                            </td>
                                            <td class="px-10 py-6 text-right text-sm font-mono text-green-500 font-black tracking-tighter">
                                                {{ number_format($client->agreed_price) }}g
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="p-20 text-center text-[10px] text-gray-700 font-black uppercase italic tracking-[0.5em]">Awaiting Asset Synchronization</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Modal -->
    <div x-data="{ open: false }" x-show="open" @open-report-modal.window="open = true" x-cloak class="fixed inset-0 z-[100] bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="card-dark w-full max-w-lg rounded-[2.5rem] p-10 relative">
            <button @click="open = false" class="absolute top-8 right-8 text-gray-500 hover:text-white">&times;</button>
            <h3 class="text-xl font-black text-white uppercase italic tracking-tighter mb-6">File Operational Report</h3>
            <form action="{{ route('raid-management.report', $raid->id) }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="text-[10px] font-black text-gray-500 uppercase block mb-1">Incident Detail</label>
                    <textarea name="content" required rows="4" placeholder="Describe the issue, client complaint, or technical failure..." class="w-full bg-gray-900 border-gray-800 rounded-xl text-white text-xs font-bold py-3"></textarea>
                </div>
                <button type="submit" class="w-full bg-red-600 p-4 rounded-xl text-[10px] font-black text-white uppercase tracking-widest transition shadow-lg shadow-red-600/20">Transmit to HQ</button>
            </form>
        </div>
    </div>

    <!-- Add Booster Modal -->
    <div x-data="{ open: false }" x-show="open" @open-add-booster-modal.window="open = true" x-cloak class="fixed inset-0 z-[100] bg-black/80 backdrop-blur-sm flex items-center justify-center p-4" @click.away="open = false">
        <div class="card-dark w-full max-w-sm rounded-[2.5rem] p-10 relative">
            <h3 class="text-xl font-black text-white uppercase italic tracking-tighter mb-6 text-center">Manual Deployment</h3>
            <form action="{{ route('raid-management.add-booster', $raid->id) }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="text-[10px] font-black text-gray-500 uppercase block mb-2">Select Verified Booster</label>
                    <select name="user_id" required class="w-full bg-gray-900 border-gray-800 rounded-xl text-white text-xs font-bold py-3 uppercase tracking-tighter">
                        <option value="">-- STANDBY LIST --</option>
                        @foreach($allBoosters as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full bg-indigo-600 p-4 rounded-xl text-[10px] font-black text-white uppercase tracking-widest transition shadow-lg shadow-indigo-600/20">Init Deployment</button>
            </form>
        </div>
    </div>
</x-app-layout>
