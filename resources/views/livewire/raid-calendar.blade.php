<div class="glass-card border border-slate-800/50 rounded-3xl p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-[10px] font-black text-white uppercase tracking-[0.2em]">Temporal Ops Grid</h3>
        <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">{{ $currentMonthName }} {{ $year }}</span>
    </div>

    <div class="grid grid-cols-7 gap-1">
        @foreach(['S','M','T','W','T','F','S'] as $day)
        <div class="text-center py-2 text-[8px] font-black text-slate-600 uppercase">{{ $day }}</div>
        @endforeach

        <!-- Padding for first day of week -->
        @for($i = 0; $i < $firstDayOfWeek; $i++)
        <div class="aspect-square"></div>
        @endfor

        @for($day = 1; $day <= $daysInMonth; $day++)
        <div class="aspect-square relative flex items-center justify-center rounded-lg {{ isset($events[$day]) ? 'bg-indigo-600/20 border border-indigo-500/30' : 'hover:bg-white/5' }} transition group cursor-help">
            <span class="text-[10px] font-bold {{ isset($events[$day]) ? 'text-white' : 'text-slate-600 group-hover:text-slate-400' }}">{{ $day }}</span>
            @if(isset($events[$day]))
            <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-indigo-500 rounded-full"></div>
            <!-- Simple Tooltip (CSS only) -->
            <div class="absolute z-20 bottom-full mb-2 hidden group-hover:block w-32 bg-slate-900 border border-slate-800 p-2 rounded-xl shadow-2xl">
                 <p class="text-[8px] font-black text-indigo-400 uppercase tracking-tighter">{{ $events[$day]->count() }} Deployments</p>
                 <p class="text-[7px] font-bold text-white mt-1 leading-tight">{{ $events[$day]->first()->title }}</p>
            </div>
            @endif
        </div>
        @endfor
    </div>
</div>
