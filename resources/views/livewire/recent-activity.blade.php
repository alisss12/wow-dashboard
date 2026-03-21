<div class="glass-card border border-slate-800/50 rounded-3xl overflow-hidden">
    <div class="px-8 py-6 border-b border-slate-800/50 flex justify-between items-center bg-slate-900/20">
        <div>
            <h3 class="text-xs font-black text-white uppercase tracking-[0.2em]">Transaction Registry</h3>
            <p class="text-[10px] font-bold text-slate-500 uppercase mt-1">Real-time ledger audit</p>
        </div>
        <a href="#" class="text-[10px] font-black text-indigo-400 hover:text-indigo-300 uppercase tracking-widest transition">View Full Ledger</a>
    </div>

    <div class="divide-y divide-slate-800/30">
        @forelse($transactions as $tx)
        <div class="px-8 py-5 flex items-center justify-between hover:bg-white/[0.02] transition">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $tx->amount > 0 ? 'bg-emerald-500/10 text-emerald-500' : 'bg-rose-500/10 text-rose-500' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($tx->amount > 0)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                        @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                        @endif
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-black text-white uppercase tracking-wider">{{ $tx->description ?? 'Financial Transmission' }}</p>
                    <p class="text-[10px] font-bold text-slate-500 mt-0.5">{{ $tx->created_at->diffForHumans() }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs font-black font-mono {{ $tx->amount > 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                    {{ $tx->amount > 0 ? '+' : '' }}{{ number_format($tx->amount) }}G
                </p>
                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-tighter mt-0.5">Verified</p>
            </div>
        </div>
        @empty
        <div class="px-8 py-10 text-center">
            <p class="text-[11px] font-bold text-slate-600 uppercase italic">No recent financial data detected.</p>
        </div>
        @endforelse
    </div>
</div>
