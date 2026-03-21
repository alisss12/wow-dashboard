<h3 class="text-xl font-black text-white uppercase tracking-tighter mb-6 italic">Financial Archive & Wallet</h3>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Wallet Metrics -->
    <div class="lg:col-span-1 space-y-6">
        <div class="card-dark p-8 rounded-3xl border-t-4 border-t-green-500">
            <h4 class="text-[10px] font-black text-gray-500 uppercase mb-2">Available for Withdrawal</h4>
            <div class="flex items-baseline space-x-2">
                <span class="text-4xl font-black text-white font-mono tracking-tighter">{{ number_format(auth()->user()->balance) }}</span>
                <span class="text-[10px] font-black text-gray-600 font-sans italic">GOLD</span>
            </div>
            <button class="w-full mt-6 bg-green-600 hover:bg-green-500 text-white p-4 rounded-xl text-[10px] font-black uppercase tracking-widest transition shadow-lg shadow-green-600/20">Init Withdrawal Request</button>
        </div>

        <div class="card-dark p-8 rounded-3xl">
            <h4 class="text-[10px] font-black text-gray-500 uppercase mb-6 flex items-center">
                 <svg class="w-3 h-3 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                 Payment Cycle Status
            </h4>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Cycle Start:</span>
                    <span class="text-[10px] font-black text-white uppercase">{{ $paymentCycle['last_cycle_start']->format('M j, H:i') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Next Payout:</span>
                    <span class="text-[10px] font-black text-indigo-500 uppercase">{{ $paymentCycle['next_payout_date']->format('M j, H:i') }}</span>
                </div>
                <div class="pt-4 border-t border-gray-800">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-[10px] font-black text-gray-500 uppercase">Cycle Earnings:</span>
                        <span class="text-xs font-black text-green-500 font-mono">{{ number_format($paymentCycle['cycle_earnings']) }}g</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Ledger -->
    <div class="lg:col-span-2 card-dark rounded-[2.5rem] overflow-hidden">
        <div class="px-8 py-6 bg-gray-900 border-b border-gray-800 flex justify-between items-center">
            <h4 class="text-xs font-black text-white uppercase tracking-widest">Transaction History</h4>
            <span class="bg-indigo-500/10 text-indigo-400 px-3 py-1 rounded text-[8px] font-black uppercase">Last 10 Actions</span>
        </div>
        <div class="divide-y divide-gray-800">
            @forelse($recentTransactions as $tx)
                <div class="px-8 py-5 flex items-center justify-between hover:bg-white/5 transition">
                    <div class="flex items-center space-x-6">
                        <div class="w-10 h-10 rounded-xl {{ $tx->amount > 0 ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500' }} flex items-center justify-center">
                            @if($tx->amount > 0)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-[10px] font-black {{ $tx->amount > 0 ? 'text-green-500' : 'text-red-500' }} uppercase tracking-widest">{{ str_replace('_', ' ', $tx->type) }}</p>
                            <p class="text-xs font-bold text-gray-400 tracking-tight">{{ $tx->description ?: 'Mission Payout Execution' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-black {{ $tx->amount > 0 ? 'text-green-500' : 'text-white' }} font-mono tracking-tighter">
                            {{ $tx->amount > 0 ? '+' : '' }}{{ number_format($tx->amount) }}
                        </p>
                        <p class="text-[9px] font-bold text-gray-600 uppercase">{{ $tx->created_at->format('M j, Y') }}</p>
                    </div>
                </div>
            @empty
                <div class="p-20 text-center text-[10px] text-gray-700 font-black uppercase tracking-widest italic">No financial movements detected in the archive.</div>
            @endforelse
        </div>
    </div>
</div>
