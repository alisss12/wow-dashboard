@props(['runningRuns'])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($runningRuns as $run)
        <div class="card-dark p-6 rounded-2xl border-l-4 border-green-500">
            <div class="flex justify-between items-start mb-4">
                <span class="text-[10px] font-black text-gray-500 uppercase">RUN #{{ $run->id }}</span>
                @if($run->is_queue && $run->service_category === 'Mythic+')
                    <span class="px-2 py-0.5 bg-indigo-500/10 text-indigo-400 text-[8px] font-black uppercase rounded">M+ Protocol</span>
                @else
                    <span class="px-2 py-0.5 bg-green-500/10 text-green-500 text-[8px] font-black uppercase rounded">Raid Mission</span>
                @endif
            </div>
            <div class="flex justify-between items-baseline">
                <h4 class="text-white font-bold flex items-center">
                    @if($run->is_queue && $run->service_category === 'Mythic+')
                        <span class="text-indigo-500 mr-2">+{{ $run->mythic_plus_level }}</span>
                    @endif
                    {{ $run->title }}
                </h4>
                <span class="text-xs font-mono text-green-500 font-black">{{ number_format($run->signups->where('is_booster', false)->sum('agreed_price')) }}g</span>
            </div>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $run->instance_name }}</p>
            
            <div class="mt-6 flex justify-between items-center pt-4 border-t border-gray-800">
                <div class="flex space-x-1">
                    @foreach($run->signups->where('is_booster', true) as $b)
                        <div class="w-6 h-6 rounded bg-indigo-500/20 flex items-center justify-center text-[7px] font-black text-indigo-400" title="{{ $b->character_name }}">{{ substr($b->character_name, 0, 1) }}</div>
                    @endforeach
                </div>
                <a href="/admin/raid-events/{{ $run->id }}/edit" class="text-[9px] font-black text-indigo-400 uppercase tracking-widest hover:text-indigo-300">Control Run</a>
            </div>
        </div>
    @empty
        <div class="col-span-full p-20 text-center card-dark border-dashed rounded-3xl opacity-50">
            <p class="text-[11px] font-black text-gray-600 uppercase tracking-tighter">No live operations currently running.</p>
        </div>
    @endforelse
</div>
