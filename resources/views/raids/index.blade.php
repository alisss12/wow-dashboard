<x-app-layout>
    <style>
        .page-dark { background-color: #0f111a; color: #9ca3af; }
        .card-dark { background-color: #161922; border: 1px solid #1f2937; transform: translateZ(0); }
        .text-accent { color: #6366f1; }
        .bg-accent { background-color: #6366f1; }
        .label-dark { color: #4b5563; font-weight: 900; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.15em; }
    </style>

    <div class="min-h-screen page-dark pb-12">
        <div class="bg-[#161922] border-b border-gray-800 shadow-2xl mb-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h2 class="text-4xl font-black text-white italic tracking-tighter uppercase">Raid Protocol Calendar</h2>
                        <p class="text-indigo-400 text-xs font-bold uppercase tracking-widest mt-2">Active Deployment Windows</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right hidden md:block">
                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest block">System Status</span>
                            <span class="text-xs font-black text-green-500 uppercase tracking-tight italic">All Nodes Operational</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($raids as $raid)
                <div class="card-dark group hover:border-indigo-500/50 transition duration-500 rounded-3xl overflow-hidden flex flex-col relative">
                    <div class="absolute top-0 right-0 p-6">
                        <span class="px-2 py-0.5 bg-indigo-500/10 border border-indigo-500/20 rounded text-[9px] font-black text-indigo-400 uppercase tracking-widest">
                            {{ $raid->status }}
                        </span>
                    </div>

                    <div class="p-8 pb-4">
                        <div class="mb-4">
                             <div class="bg-indigo-600/10 border border-indigo-500/20 rounded-xl px-3 py-1.5 w-max">
                                <span class="text-[10px] font-black uppercase text-indigo-400 tracking-widest">{{ $raid->scheduled_at->format('M j, Y') }}</span>
                            </div>
                        </div>

                        <h3 class="text-xl font-black text-white uppercase tracking-tighter mb-1">{{ $raid->title }}</h3>
                        <div class="flex items-center space-x-2 mb-6">
                            <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest">{{ $raid->instance_name }} / {{ $raid->difficulty }}</span>
                            <span class="text-[8px] bg-indigo-500/10 text-indigo-400 px-1.5 py-0.5 rounded font-black uppercase tracking-tighter">{{ $raid->service_category }}</span>
                            <span class="text-[8px] bg-purple-500/10 text-purple-400 px-1.5 py-0.5 rounded font-black uppercase tracking-tighter">{{ $raid->region }}</span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 py-4 border-t border-gray-800/50">
                            <div>
                                <span class="label-dark block mb-1">Time</span>
                                <span class="text-xs font-black text-gray-300 uppercase italic">{{ $raid->scheduled_at->format('g:i A') }}</span>
                            </div>
                            <div>
                                <span class="label-dark block mb-1">Capacity</span>
                                <span class="text-xs font-black text-gray-300 uppercase italic">{{ $raid->signups()->count() }} / {{ $raid->max_players }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 pt-0 mt-auto">
                        <a href="{{ route('raids.show', $raid->id) }}" class="flex items-center justify-between w-full bg-[#0c0e14] group-hover:bg-indigo-600 border border-gray-800 group-hover:border-indigo-500 text-gray-400 group-hover:text-white px-6 py-4 rounded-2xl transition duration-500 overflow-hidden relative">
                            <span class="text-[10px] font-black uppercase tracking-widest relative z-10">Access Data Node</span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 py-24 text-center card-dark border-dashed rounded-3xl bg-gray-900/10">
                    <h4 class="text-gray-700 font-black uppercase tracking-widest">No Protocol Windows Scheduled</h4>
                    <p class="text-gray-800 text-[10px] font-bold mt-2 uppercase">Awaiting tactical data from command center.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
