@props(['applications'])

<div class="space-y-4">
    @forelse($applications as $app)
        <div class="card-dark p-4 rounded-2xl flex items-center justify-between border-l-4 border-indigo-500 hover:bg-white/5 transition duration-300">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-indigo-600/20 rounded-xl flex items-center justify-center border border-indigo-500/30">
                    <span class="text-indigo-400 font-black text-xs">{{ substr($app->character_name, 0, 1) }}</span>
                </div>
                <div>
                    <h4 class="text-[11px] font-black text-white italic tracking-tight">{{ $app->character_name }}</h4>
                    <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mt-0.5">Applying for: <span class="text-gray-300">{{ $app->event->title ?? 'Unknown Mission' }}</span></p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <form action="{{ route('raid-management.approve-booster', [$app->raid_event_id, $app->id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition shadow-lg shadow-indigo-600/20">Authorize</button>
                </form>
                
                <form action="{{ route('raid-management.reject-booster', [$app->raid_event_id, $app->id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="text-[9px] font-black text-red-500/60 hover:text-red-500 uppercase tracking-widest transition">Decline</button>
                </form>
            </div>
        </div>
    @empty
        <div class="p-12 text-center card-dark border-dashed rounded-[2rem] opacity-50">
            <p class="text-[10px] font-black text-gray-700 uppercase tracking-[0.2em] font-mono italic">Sentinel: No active personnel applications detected</p>
        </div>
    @endforelse
</div>
