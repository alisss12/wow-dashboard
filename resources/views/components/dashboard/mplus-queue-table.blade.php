@props(['mplusQueue'])

<table class="w-full text-left">
    <thead class="bg-gray-900 border-b border-gray-800 text-[10px] font-black text-gray-500 uppercase">
        <tr>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4">Service</th>
            <th class="px-6 py-4">Client</th>
            <th class="px-6 py-4">Level</th>
            <th class="px-6 py-4">Price</th>
            @if(in_array(auth()->user()->account_type, ['admin', 'staff']))
                <th class="px-6 py-4">Advertiser</th>
            @endif
            <th class="px-6 py-4 text-right">Action</th>
        </tr>
    <tbody class="divide-y divide-gray-800">
        @forelse($mplusQueue as $order)
            <tr class="hover:bg-white/5 transition cursor-pointer" @click="openMplusOrderId = (openMplusOrderId === {{ $order->id }} ? null : {{ $order->id }})">
                <td class="px-6 py-4">
                    @if($order->status === 'scouting')
                        <span class="px-2 py-1 rounded bg-yellow-500/10 text-yellow-500 text-[8px] font-black uppercase">Scouting</span>
                    @else
                        <span class="px-2 py-1 rounded bg-indigo-500/10 text-indigo-400 text-[8px] font-black uppercase tracking-widest">Awaiting Squad</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-[10px] font-black text-white uppercase tracking-tighter">
                    {{ $order->service_type ?? 'Mythic+ Key' }}
                </td>
                <td class="px-6 py-4 text-xs font-bold text-gray-300">
                    <div class="flex items-center">
                        @php $s = $order->signups->where('is_booster', false)->first(); @endphp
                        <span>{{ $s->character_name ?? 'N/A' }}</span>
                        @php $client = $order->advertiser; @endphp
                        @if($client?->type === 'vip')
                            <span class="ml-1 text-yellow-500 text-[10px]" title="VIP Client">⭐</span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 text-xs font-black text-indigo-400 text-lg">+{{ $order->mythic_plus_level }}</td>
                <td class="px-6 py-4 text-xs font-mono text-green-500">
                    <div class="flex flex-col">
                        <span class="font-black text-sm">{{ number_format($order->price_per_spot) }}g</span>
                        @if($s)
                            <span class="text-[8px] font-black uppercase {{ $s->is_paid ? 'text-green-500' : 'text-red-500' }}">
                                {{ $s->is_paid ? 'Paid' : 'Unpaid' }}
                            </span>
                        @endif
                    </div>
                </td>
                @if(in_array(auth()->user()->account_type, ['admin', 'staff']))
                    <td class="px-6 py-4 text-[9px] font-black text-indigo-400 uppercase tracking-tighter">
                        {{ $order->advertiser->name ?? 'System' }}
                    </td>
                @endif
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end space-x-3">
                        @if(auth()->user()->account_type === 'booster')
                            <button @click.stop="$dispatch('open-join-mplus', { eventId: {{ $order->id }} })" class="bg-indigo-600/10 text-indigo-400 px-4 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition">Join Squad</button>
                        @endif

                        @php $sId = $order->signups->where('is_booster', false)->first()?->id; @endphp
                        @if($sId && ($order->advertiser_user_id === auth()->id() || in_array(auth()->user()->account_type, ['admin', 'staff'])))
                            @php $s = $order->signups->where('is_booster', false)->first(); @endphp
                            
                            @if(!$s->is_paid)
                                <form action="{{ route('signups.confirmPayment', $s->id) }}" method="POST" @click.stop="">
                                    @csrf
                                    <button type="submit" class="bg-green-500 text-white px-3 py-1.5 rounded text-[8px] font-black uppercase tracking-widest hover:bg-green-400 transition shadow-lg shadow-green-500/20">Confirm Gold</button>
                                </form>
                            @endif

                            <button @click.stop="$dispatch('open-edit-order-modal', { 
                                id: {{ $s->id }}, 
                                char: '{{ $s->character_name }}',
                                realm: '{{ $s->buyer_realm }}',
                                faction: '{{ $s->buyer_faction }}',
                                role: '{{ $s->role }}',
                                class: '{{ $s->class }}',
                                spec: '{{ $s->spec }}',
                                armor: '{{ $s->armor_type }}',
                                price: {{ $s->agreed_price }},
                                discord: '{{ $s->client_discord }}',
                                priority: '{{ $s->loot_priority }}',
                                notes: '{{ $s->notes }}'
                            })" class="text-[9px] font-black text-gray-400 uppercase hover:text-white transition">Edit</button>

                            <form action="{{ route('signups.destroy', $sId) }}" method="POST" onsubmit="return confirm('Terminate this Mythic+ request?')" @click.stop="">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[9px] font-black text-red-500/50 uppercase hover:text-red-500 transition">Cancel</button>
                            </form>
                        @endif
                        
                        <div class="flex items-center ml-2 text-gray-700">
                             @php $appCount = $order->signups->where('is_booster', true)->count(); @endphp
                             <span class="text-[10px] transform transition-transform duration-200" :class="openMplusOrderId === {{ $order->id }} ? 'rotate-180' : ''">▼</span>
                        </div>
                    </div>
                </td>
            </tr>

            <!-- EXPANDED APPLICATIONS & DETAILS -->
            <tr x-show="openMplusOrderId === {{ $order->id }}" x-cloak class="bg-black/40">
                <td colspan="{{ in_array(auth()->user()->account_type, ['admin', 'staff']) ? 7 : 6 }}" class="px-8 py-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 animate-in fade-in slide-in-from-top-1 duration-300">
                        <!-- Pending Booster Squads -->
                        <div class="space-y-4">
                            <div class="text-[8px] font-black text-gray-500 uppercase tracking-widest mb-2 flex justify-between items-center border-b border-gray-800 pb-2">
                                <span>Pending Applications (Waitlist)</span>
                                <span class="bg-indigo-500/20 text-indigo-400 px-2 py-0.5 rounded text-[8px]">{{ $order->signups->where('is_booster', true)->where('status', 'pending')->count() }}</span>
                            </div>
                            @forelse($order->signups->where('is_booster', true)->where('status', 'pending') as $app)
                                <div class="flex items-center justify-between group p-3 bg-gray-900/40 border border-gray-800/50 hover:border-indigo-500/30 rounded-xl transition">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-xl bg-gray-800 flex items-center justify-center text-xs font-black text-gray-500 group-hover:bg-indigo-500/20 group-hover:text-indigo-400 transition">
                                            {{ substr($app->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-black text-white uppercase">{{ $app->user->name ?? 'Unknown' }}</p>
                                            <p class="text-[9px] text-gray-500 font-bold uppercase tracking-tighter">{{ $app->character_name }} • {{ $app->role }} • {{ $app->class }}</p>
                                        </div>
                                    </div>
                                    @if($order->advertiser_user_id === auth()->id() || in_array(auth()->user()->account_type, ['admin', 'staff']))
                                        <form action="{{ route('raid-requests.assign', ['raidRequest' => $order->id, 'signup' => $app->id]) }}" method="POST" @click.stop="">
                                            @csrf
                                            <button type="submit" class="bg-green-500/10 text-green-500 px-4 py-2 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-green-500 hover:text-white transition">Select Squad</button>
                                        </form>
                                    @endif
                                </div>
                            @empty
                                <div class="py-8 text-center bg-gray-900/20 rounded-xl border border-dashed border-gray-800">
                                    <div class="text-[9px] font-black text-gray-600 uppercase tracking-widest">Searching for qualified squads...</div>
                                </div>
                            @endforelse
                        </div>

                        <!-- Order Intel / Notes -->
                        <div class="space-y-4 border-l border-gray-800 pl-12">
                             <div class="text-[8px] font-black text-gray-500 uppercase tracking-widest mb-2 border-b border-gray-800 pb-2">Operational Intel</div>
                             @php $s = $order->signups->where('is_booster', false)->first(); @endphp
                             @if($s)
                                <div class="grid grid-cols-2 gap-8">
                                    <div class="space-y-1">
                                        <p class="text-[8px] text-gray-600 font-bold uppercase tracking-widest">Target Asset</p>
                                        <p class="text-xs text-white font-black">{{ $s->character_name }}</p>
                                        <p class="text-[9px] text-gray-500 font-bold uppercase">{{ $s->buyer_realm }} • {{ $s->buyer_faction }}</p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-[8px] text-gray-600 font-bold uppercase tracking-widest">Loadout</p>
                                        <p class="text-xs text-indigo-400 font-black uppercase">{{ $s->role }} - {{ $s->class }}</p>
                                        <p class="text-[9px] text-gray-500 font-bold uppercase">{{ $s->armor_type }} • {{ $s->loot_priority }} priority</p>
                                    </div>
                                    <div class="col-span-2 space-y-2">
                                        <p class="text-[8px] text-gray-600 font-bold uppercase tracking-widest">Strategic Notes</p>
                                        <div class="p-4 bg-gray-900/60 rounded-xl border border-gray-800 text-[10px] text-gray-400 font-bold italic leading-relaxed">
                                            {{ $s->notes ?: 'No special instructions provided by the client.' }}
                                        </div>
                                    </div>
                                </div>
                             @endif
                        </div>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="{{ in_array(auth()->user()->account_type, ['admin', 'staff']) ? 7 : 6 }}" class="p-16 text-center text-[10px] text-gray-600 uppercase font-black tracking-[0.3em]">Operational Queue Empty</td></tr>
        @endforelse
    </tbody>
</table>
