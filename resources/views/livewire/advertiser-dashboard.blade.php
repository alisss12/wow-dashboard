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
            <button @click="showPostModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-bold transition shadow-sm border border-indigo-700">
                Post New Booking
            </button>
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
                    <h3 class="text-lg font-bold text-gray-900">My Active Bookings</h3>
                </div>
                <div class="overflow-x-auto flex-grow">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4">Raid Focus</th>
                                <th class="px-6 py-4">Total Price</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($activeBookings as $booking)
                            <tr class="hover:bg-gray-50 transition cursor-pointer" wire:click="generateMailString({{ $booking->id }})">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900">{{ $booking->event->title ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $booking->event->scheduled_at?->format('d M — H:i') }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-indigo-600">{{ number_format($booking->agreed_price) }} G</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="px-6 py-12 text-center text-sm text-gray-500 bg-gray-50/30">No active bookings under your partner ID.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Available Deployable Runs -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col h-full">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900">Available Pre-scheduled Runs</h3>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($availableRaids as $raid)
                    <div class="bg-white border border-gray-200 rounded-xl p-5 hover:border-indigo-500 hover:shadow-md transition cursor-pointer relative overflow-hidden group">
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
                        Data refreshes every 30 seconds
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
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Show only VIP raids with slots available for:</span>
                    <div class="flex items-center gap-2">
                        <button class="w-8 h-8 rounded-md bg-white border border-gray-300 text-xs font-bold text-gray-700 hover:border-indigo-500 hover:text-indigo-600 transition shadow-sm">C</button>
                        <button class="w-8 h-8 rounded-md bg-white border border-gray-300 text-xs font-bold text-gray-700 hover:border-indigo-500 hover:text-indigo-600 transition shadow-sm">L</button>
                        <button class="w-8 h-8 rounded-md bg-white border border-gray-300 text-xs font-bold text-gray-700 hover:border-indigo-500 hover:text-indigo-600 transition shadow-sm">M</button>
                        <button class="w-8 h-8 rounded-md bg-white border border-gray-300 text-xs font-bold text-gray-700 hover:border-indigo-500 hover:text-indigo-600 transition shadow-sm">P</button>
                    </div>
                </div>
            </div>

            <div class="p-0 overflow-x-auto">
                <!-- Enriched Data Table -->
                @if(count($activeBookings) > 0)
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-white text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">Raid Name & Status</th>
                            <th class="px-6 py-4 text-center">Buyers<br><span class="text-gray-300 text-[9px]">C L M P</span></th>
                            <th class="px-6 py-4">Date & Time</th>
                            <th class="px-6 py-4 text-right">Pot Size</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($activeBookings as $booking)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-orange-500"></div>
                                    <div>
                                        <p class="text-sm font-bold text-purple-700">{{ $booking->event->title ?? 'N/A' }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-700 uppercase tracking-wider">Locked</span>
                                            <span class="text-xs text-gray-500 font-medium">{{ $booking->event->difficulty ?? 'Mythic' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="flex gap-1 mb-1 text-[10px] font-bold text-red-500">
                                        <span>C</span><span>L</span><span>M</span><span>P</span>
                                    </div>
                                    <span class="text-sm font-bold text-gray-900 bg-gray-100 px-3 py-1 rounded-full border border-gray-200">0 / 4</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-emerald-600">{{ $booking->event?->scheduled_at?->format('d M — H:i') }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-base font-black text-gray-900">{{ number_format($booking->agreed_price) }} <span class="text-indigo-600 text-xs">G</span></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button wire:click="generateMailString({{ $booking->id }})" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Copy Buyer Bill">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </button>
                                    <button class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Broadcast">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-24 bg-white">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4 border border-gray-100">
                        <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <h4 class="text-base font-bold text-gray-900 mb-1">No Active Raids Found</h4>
                    <p class="text-sm text-gray-500 font-medium">There are currently no marketing entries for this specific tier.</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        @if(in_array($activeTab, ['balance', 'deposits', 'web_bookings', 'gold_collection']))
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900 capitalize">{{ str_replace('_', ' ', $activeTab) }} Accounting</h3>
            </div>
            <div class="p-8 text-center max-w-2xl mx-auto">
                <svg class="w-16 h-16 text-indigo-500/20 mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-11v6h2v-6h-2zm0-4v2h2V7h-2z"/></svg>
                <div class="text-5xl font-black text-gray-900 mb-2">{{ number_format(auth()->user()->balance()) }} <span class="text-indigo-500 text-2xl">G</span></div>
                <div class="text-xs text-gray-500 uppercase tracking-widest font-bold mt-4">Calculated Advertiser Cut</div>
                
                <div class="mt-8 p-6 bg-gray-50 rounded-xl border border-gray-200">
                    <p class="text-sm font-medium text-gray-600">Pending commissions are credited post-raid completion by boosters.</p>
                </div>
            </div>
        </div>
        @endif

    </div>

    <!-- Post Booking Modal -->
    <div x-show="showPostModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4 shadow-2xl" x-cloak x-transition>
        <div @click="showPostModal = false" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-lg bg-white rounded-2xl p-8 shadow-2xl border border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Create Marketing Request</h2>
            <form wire:submit.prevent="postNewRequest" class="space-y-4">
                <input type="text" wire:model="title" placeholder="Raid Title (e.g. Heroic Vault)" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-900 font-medium focus:border-indigo-500 outline-none">
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" wire:model="instance" placeholder="Instance" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-900 font-medium focus:border-indigo-500 outline-none">
                    <select wire:model="difficulty" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-900 font-medium focus:border-indigo-500 outline-none">
                        <option value="">Difficulty</option>
                        <option value="Normal">Normal</option>
                        <option value="Heroic">Heroic</option>
                        <option value="Mythic">Mythic</option>
                    </select>
                </div>
                <input type="datetime-local" wire:model="scheduled_at" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-900 font-medium focus:border-indigo-500 outline-none">
                <input type="number" wire:model="price" placeholder="Agreed Buyer Price (Gold)" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-900 font-medium focus:border-indigo-500 outline-none">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 pt-3.5 rounded-lg text-sm transition uppercase tracking-widest mt-2 shadow-md">
                    Submit Request
                </button>
            </form>
        </div>
    </div>
</div>
