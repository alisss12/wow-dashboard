<div class="space-y-8 relative" x-data="{ 
    showPostModal: @entangle('showPostModal')
}">
    
    <!-- Watermark Logo -->
    <div class="fixed inset-0 pointer-events-none flex items-center justify-center opacity-[0.03] z-0 overflow-hidden">
        <svg viewBox="0 0 24 24" fill="currentColor" class="w-[800px] h-[800px] text-gray-900"><path d="M12 2L15 8H9L12 2Z M4 12L8 9V15L4 12Z M20 12L16 15V9L20 12Z"/></svg>
    </div>

    <div class="relative z-10">
        <!-- Top Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                    @if(in_array($activeTab, ['balance', 'deposits', 'web_bookings', 'gold_collection']))
                        Finances & Deposits
                    @elseif(in_array($activeTab, ['full_raids', 'curves', 'mythic_raids', 'legacy', 'all_raids']))
                        Raid Marketing
                    @else
                        Partner Dashboard
                    @endif
                </h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Manage your bookings, generate leads, and track payouts.</p>
            </div>
            <!-- Global Post Booking removed as Raid Leaders handle Raid Creation -->
        </div>

        @if(empty($activeTab) || $activeTab === 'overview' || $activeTab === 'available')
        <!-- Stats Matrix -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                 <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Total Sales Generated</p>
                 <h3 class="text-3xl font-black text-gray-900">{{ number_format($stats['total_sales'] ?? 0) }} <span class="text-indigo-600 text-lg">G</span></h3>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                 <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Live Pending Bookings</p>
                 <h3 class="text-3xl font-black text-gray-900">{{ $stats['active_bookings'] ?? 0 }}</h3>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm border-l-4 border-indigo-500">
                 <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">My Commission</p>
                 <h3 class="text-3xl font-black text-indigo-600 leading-none">{{ number_format($stats['commission_earned'] ?? 0) }} <span class="text-gray-400 text-lg">G</span></h3>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
            <!-- My Active Bookings -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col h-full">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900">My Secured Clients</h3>
                </div>
                <div class="overflow-x-auto flex-grow">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                             <tr>
                                <th class="px-6 py-4">Raid Focus</th>
                                <th class="px-6 py-4 text-center">VIP</th>
                                <th class="px-6 py-4">Difficulty</th>
                                <th class="px-6 py-4">Leader</th>
                                <th class="px-6 py-4">Buyer</th>
                                <th class="px-6 py-4">Total Price</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($activeBookings as $booking)
                             <tr class="hover:bg-gray-50 transition cursor-pointer" wire:click="generateMailString({{ $booking->id }})">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900">{{ $booking->event->title ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $booking->event->scheduled_at?->format('d M — H:i') }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <svg class="w-4 h-4 text-gray-400 mx-auto" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15 8H9L12 2Z M4 12L8 9V15L4 12Z M20 12L16 15V9L20 12Z"/></svg>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $booking->event->difficulty ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $booking->event->leader_name ?? 'TBD' }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                    <p>{{ $booking->character_name ?? 'N/A' }}</p>
                                    <p class="text-[10px] text-gray-400 uppercase tracking-tighter">{{ $booking->buyer_realm ?? '' }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-indigo-600">{{ number_format($booking->agreed_price) }} G</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColor = match($booking->status) {
                                            'accepted' => 'bg-emerald-100 text-emerald-700',
                                            'pending' => 'bg-amber-100 text-amber-700',
                                            'completed' => 'bg-indigo-100 text-indigo-700',
                                            default => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $statusColor }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-sm text-gray-500 bg-gray-50/30">No active bookings under your partner ID.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Dashboard Mini Market -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col h-full">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900">Available Pre-scheduled Runs</h3>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse(collect($availableRaids)->take(4) as $raid)
                    <div class="bg-white border border-gray-200 rounded-xl p-5 hover:border-indigo-500 hover:shadow-md transition cursor-pointer relative overflow-hidden group" wire:click="openAddBuyerModal({{ $raid->id }})">
                        <div class="absolute inset-x-0 bottom-0 h-1 bg-indigo-500 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
                        <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest block mb-1">Sellable Slot</span>
                        <h4 class="text-sm font-bold text-gray-900">{{ $raid->title }}</h4>
                        <p class="text-xs text-gray-500 mt-1 font-medium">{{ $raid->difficulty }} Core</p>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-10 bg-gray-50/30 rounded-lg border border-dashed border-gray-300">
                        <p class="text-sm text-gray-500 font-medium">No pre-scheduled runs to sell right now.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endif

        @if(in_array($activeTab, ['full_raids', 'curves', 'mythic_raids', 'legacy', 'all_raids']))
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex flex-col gap-4 relative">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-black text-gray-900 capitalize flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        DawnHub | Raid Bookings
                    </h3>
                    
                    <div class="flex items-center gap-2 text-xs font-bold text-gray-500 bg-white px-3 py-1.5 rounded-md border border-gray-200 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Market refreshes every 30 seconds
                        <button class="ml-2 hover:text-indigo-600 transition" wire:click="$refresh">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Advanced Navigation Controls -->
                <div class="flex flex-wrap items-center justify-between gap-4 mt-2">
                    <div class="flex items-center gap-1 bg-gray-200 p-1 rounded-lg">
                        <button class="px-4 py-1.5 text-xs font-bold text-gray-600 hover:text-gray-900 hover:bg-white rounded-md transition">Last Cycle</button>
                        <button class="px-4 py-1.5 text-xs font-bold text-indigo-700 bg-white shadow-sm rounded-md transition">Current Cycle</button>
                        <button class="px-4 py-1.5 text-xs font-bold text-gray-600 hover:text-gray-900 hover:bg-white rounded-md transition">Next Cycle</button>
                    </div>

                    <div class="flex items-center gap-1 bg-gray-200 p-1 rounded-lg">
                        <button class="px-4 py-1.5 text-xs font-bold text-gray-600 hover:text-gray-900 hover:bg-white rounded-md transition">-1 Day</button>
                        <button class="px-4 py-1.5 text-xs font-bold text-indigo-700 bg-white shadow-sm rounded-md transition">Today</button>
                        <button class="px-4 py-1.5 text-xs font-bold text-gray-600 hover:text-gray-900 hover:bg-white rounded-md transition">+1 Day</button>
                    </div>
                </div>

                <!-- VIP Armor Filtering -->
                <div class="flex flex-wrap items-center gap-3 mt-2 border-t border-gray-200 pt-4">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Available Raids filtering (Armor Stacks):</span>
                    <div class="flex items-center gap-2">
                        <button class="w-8 h-8 rounded-md bg-white border border-gray-300 text-xs font-bold text-gray-700 hover:border-indigo-500 hover:text-indigo-600 transition shadow-sm">C</button>
                        <button class="w-8 h-8 rounded-md bg-white border border-gray-300 text-xs font-bold text-gray-700 hover:border-indigo-500 hover:text-indigo-600 transition shadow-sm">L</button>
                        <button class="w-8 h-8 rounded-md bg-white border border-gray-300 text-xs font-bold text-gray-700 hover:border-indigo-500 hover:text-indigo-600 transition shadow-sm">M</button>
                        <button class="w-8 h-8 rounded-md bg-white border border-gray-300 text-xs font-bold text-gray-700 hover:border-indigo-500 hover:text-indigo-600 transition shadow-sm">P</button>
                    </div>
                </div>
            </div>

            <div class="p-0 overflow-x-auto">
                <!-- DawnHub Exact Table (Marketplace) -->
                @if(count($availableRaids) > 0)
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-gray-900 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-800">
                        <tr>
                            <th class="px-6 py-4">Time</th>
                            <th class="px-6 py-4">Raid Focus</th>
                            <th class="px-6 py-4 text-center">VIP</th>
                            <th class="px-6 py-4">Difficulty</th>
                            <th class="px-6 py-4">Leader</th>
                            <th class="px-6 py-4">Base Pot</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800 bg-[#222]">
                        @foreach($availableRaids as $raid)
                        <tr class="hover:bg-[#2a2a2a] transition-colors group">
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-emerald-500">{{ $raid->scheduled_at ? \Carbon\Carbon::parse($raid->scheduled_at)->format('d M — H:i') : 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-purple-400">{{ $raid->title ?? 'N/A' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <svg class="w-4 h-4 text-gray-500 mx-auto" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15 8H9L12 2Z M4 12L8 9V15L4 12Z M20 12L16 15V9L20 12Z"/></svg>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-300">{{ $raid->difficulty ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-300">{{ $raid->leader_name ?? 'TBD' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-100">{{ number_format($raid->price_per_spot ?? 0) }} G</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-xs font-bold bg-emerald-500/20 text-emerald-400">Available</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="openAddBuyerModal({{ $raid->id }})" class="bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] font-bold uppercase tracking-widest px-4 py-2 rounded-lg transition shadow-sm">
                                    Book Slot
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-24 bg-[#222]">
                    <h4 class="text-base font-bold text-gray-300 mb-1">No Available Raids Found</h4>
                    <p class="text-sm text-gray-500 font-medium">There are currently no marketing entries for this specific tier.</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        @if(in_array($activeTab, ['balance', 'deposits', 'web_bookings', 'gold_collection']))
        <div class="bg-[#222] border border-gray-800 rounded-xl shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-gray-800 bg-[#2b2b2b]">
                <h3 class="text-lg font-bold text-gray-100 capitalize">{{ str_replace('_', ' ', $activeTab) }} Accounting</h3>
            </div>
            <div class="p-8 text-center max-w-2xl mx-auto">
                <div class="text-5xl font-black text-white mb-2">{{ number_format(auth()->user()->balance()) }} <span class="text-emerald-500 text-2xl">G</span></div>
                <div class="text-xs text-gray-500 uppercase tracking-widest font-bold mt-4">Calculated Advertiser Cut</div>
            </div>
        </div>
        @endif

    </div>

    <!-- Add Buyer / Booking Modal -->
    <div x-show="showPostModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4 shadow-2xl" x-cloak x-transition>
        <div @click="showPostModal = false" class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-2xl bg-[#222] rounded-xl p-8 shadow-2xl border border-gray-700 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6 border-b border-gray-800 pb-4">
                <h2 class="text-xl font-bold text-white">Add Buyer to Raid</h2>
                <button @click="showPostModal = false" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form wire:submit.prevent="addBuyer" class="grid grid-cols-1 gap-5">

                <!-- Logistics -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Deposit (Gold)</label>
                        <input type="number" wire:model="deposit" class="w-full bg-[#1e1e1e] border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-white focus:border-indigo-500 outline-none" min="0">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Owes (Gold)</label>
                        <input type="number" wire:model="owes" class="w-full bg-[#1e1e1e] border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-white focus:border-indigo-500 outline-none" min="0">
                    </div>
                </div>

                <!-- Buyer Details -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Buyer Name</label>
                        <input type="text" wire:model="buyer_name" placeholder="Character Name" class="w-full bg-[#1e1e1e] border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-white focus:border-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Buyer Realm</label>
                        <input type="text" wire:model="buyer_realm" placeholder="Realm" class="w-full bg-[#1e1e1e] border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-white focus:border-indigo-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Class</label>
                        <select wire:model="character_class" class="w-full bg-[#1e1e1e] border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-white focus:border-indigo-500 outline-none">
                            <option value="">Select Class</option>
                            <option value="Warrior">Warrior</option>
                            <option value="Paladin">Paladin</option>
                            <option value="Hunter">Hunter</option>
                            <option value="Rogue">Rogue</option>
                            <option value="Priest">Priest</option>
                            <option value="Death Knight">Death Knight</option>
                            <option value="Shaman">Shaman</option>
                            <option value="Mage">Mage</option>
                            <option value="Warlock">Warlock</option>
                            <option value="Monk">Monk</option>
                            <option value="Druid">Druid</option>
                            <option value="Demon Hunter">Demon Hunter</option>
                            <option value="Evoker">Evoker</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Payment Faction/Realm</label>
                        <input type="text" wire:model="payment_realm" placeholder="e.g. Tarren Mill - Horde" class="w-full bg-[#1e1e1e] border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-white focus:border-indigo-500 outline-none">
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Public Note</label>
                    <input type="text" wire:model="public_note" class="w-full bg-[#1e1e1e] border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-white focus:border-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Private Note</label>
                    <input type="text" wire:model="private_note" class="w-full bg-[#1e1e1e] border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-white focus:border-indigo-500 outline-none">
                </div>

                <div class="flex items-center mt-2">
                    <input type="checkbox" wire:model="paid_full" id="paid_full" class="w-4 h-4 text-indigo-600 bg-gray-800 border-gray-700 rounded focus:ring-indigo-500">
                    <label for="paid_full" class="ml-2 text-sm font-medium text-gray-300">Paid in Full</label>
                </div>

                <div class="mt-4">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg text-sm transition uppercase tracking-widest shadow-md">
                        Secure Spot Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
