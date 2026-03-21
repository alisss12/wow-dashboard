<div class="glass-card border border-slate-800/50 rounded-3xl p-8">
     <div class="flex items-center justify-between mb-8">
        <div>
            <h3 class="text-xs font-black text-white uppercase tracking-[0.2em]">Efficiency Analysis</h3>
            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-1">7-Day Yield Variance</p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
            <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Gross Profit</span>
        </div>
     </div>

     <div class="h-48 flex items-end justify-between gap-3 px-2">
         @foreach($data as $item)
         @php $percent = $max > 0 ? ($item['amount'] / $max) * 100 : 5; @endphp
         <div class="flex-1 bg-indigo-600/10 rounded-t-2xl group relative hover:bg-indigo-600/30 transition-all duration-700" style="height: {{ max(10, $percent) }}%">
             <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-indigo-600 text-[9px] font-black text-white px-3 py-1.5 rounded-xl opacity-0 group-hover:opacity-100 transition shadow-xl z-10 whitespace-nowrap">
                {{ number_format($item['amount']) }}G
             </div>
             
             <!-- Glow effect for high points -->
             @if($percent > 80)
             <div class="absolute inset-x-0 bottom-0 bg-indigo-400 blur-xl opacity-20 animate-pulse h-1/2 rounded-full"></div>
             @endif
         </div>
         @endforeach
     </div>
     
     <div class="flex justify-between mt-6 border-t border-slate-800/50 pt-4">
         @foreach($data as $item)
         <span class="text-[9px] font-black text-slate-600 uppercase w-full text-center tracking-tighter">{{ $item['day'] }}</span>
         @endforeach
     </div>
</div>
