@props(['hostedRaids'])

<table class="w-full text-left">
    <thead class="bg-gray-900 border-b border-gray-800 text-[10px] font-black text-gray-500 uppercase font-mono">
        <tr>
            <th class="px-6 py-4">Protocol / Instance</th>
            <th class="px-6 py-4">Deployment</th>
            <th class="px-6 py-4 text-center">Payload</th>
            <th class="px-6 py-4 text-center">Squad</th>
            <th class="px-6 py-4 text-center">Status</th>
            <th class="px-6 py-4 text-right">Operation</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-800/50">
        @forelse($hostedRaids as $raid)
            <tr class="hover:bg-white/5 transition duration-300">
                <td class="px-6 py-4">
                    <div class="flex flex-col">
                        <span class="text-xs font-black text-white italic tracking-tight">{{ $raid->title }}</span>
                        <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mt-0.5">{{ $raid->instance_name }}</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex flex-col">
                        <span class="text-xs font-black text-indigo-400 font-mono tracking-tighter">{{ $raid->scheduled_at->format('H:i') }}</span>
                        <span class="text-[9px] font-bold text-gray-600 uppercase">{{ $raid->scheduled_at->format('M j') }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="flex flex-col items-center">
                        <span class="text-xs font-black text-green-500 font-mono">{{ number_format($raid->signups->where('is_booster', false)->sum('agreed_price')/1000, 1) }}k</span>
                        <span class="text-[8px] font-bold text-gray-600 uppercase">{{ $raid->signups->where('is_booster', false)->count() }} Clients</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="flex flex-col items-center">
                        <span class="text-xs font-black text-white italic">{{ $raid->signups->where('is_booster', true)->where('status', 'accepted')->count() }}<span class="text-[9px] opacity-30 mx-0.5">/</span>{{ $raid->max_players ?: 24 }}</span>
                        <div class="w-12 bg-black h-1 rounded-full mt-1 overflow-hidden">
                            <div class="bg-indigo-600 h-full rounded-full" style="width: {{ min(100, ($raid->signups->where('is_booster', true)->count() / (max(1, $raid->max_players ?: 24))) * 100) }}%"></div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
                    @php
                        $statusColors = [
                            'approved' => 'text-green-500',
                            'running' => 'text-indigo-400 animate-pulse',
                            'completed' => 'text-purple-500',
                            'cancelled' => 'text-red-500'
                        ];
                    @endphp
                    <span class="text-[10px] font-black uppercase tracking-widest {{ $statusColors[$raid->status] ?? 'text-gray-500' }}">{{ $raid->status }}</span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end space-x-3">
                        @if($raid->status === 'approved' || $raid->status === 'published' || $raid->status === 'ready' || $raid->status === 'roster_locked')
                            <form action="{{ route('raid-management.start', $raid->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-600/10 hover:bg-green-600 text-green-500 hover:text-white border border-green-500/20 px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition shadow-lg shadow-green-600/20">Run mission</button>
                            </form>
                        @elseif($raid->status === 'running')
                            <form action="{{ route('raid-management.complete', $raid->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-indigo-600 px-4 py-1.5 rounded-xl text-[9px] font-black text-white uppercase tracking-widest transition shadow-lg shadow-indigo-600/40 hover:bg-indigo-500 animate-pulse">Finalize</button>
                            </form>
                        @endif

                        <a href="{{ route('raids.show', $raid->id) }}?tab=my_runs" class="bg-gray-800/10 hover:bg-gray-800 text-gray-400 hover:text-white border border-gray-700/50 px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition">Manage</a>
                        
                        @php 
                            $isPast = now()->greaterThan($raid->scheduled_at);
                            $isNear = now()->greaterThanOrEqualTo($raid->scheduled_at->copy()->subMinutes(30));
                            $canCancel = !$isPast && !$isNear && !in_array($raid->status, ['running', 'completed']);
                        @endphp

                        @if($canCancel)
                            <button @click="$dispatch('open-cancel-raid-modal', { id: {{ $raid->id }}, title: '{{ $raid->title }}' })" class="text-[9px] font-black text-red-500/60 hover:text-red-500 uppercase tracking-widest">Abort</button>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="p-20 text-center text-xs font-black text-gray-700 uppercase tracking-[0.3em] font-mono italic">Zero Protocol Mandates Detected</td>
            </tr>
        @endforelse
    </tbody>
</table>
