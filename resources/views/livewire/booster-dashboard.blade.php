<div class="space-y-8 relative" x-data="{ 
    showSubmitModal: @entangle('showSubmitModal'),
    showCreateModal: @entangle('showCreateModal')
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
                        Balance & Finance
                    @elseif(in_array($activeTab, ['full_raids', 'curves', 'mythic_raids', 'legacy', 'all_raids']))
                        Raid Bookings
                    @else
                        Booster Dashboard
                    @endif
                </h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Welcome back, {{ auth()->user()->name }}. Monitor your runs and gold payouts.</p>
            </div>
            <div class="flex items-center gap-3">
                <button @click="showCreateModal = true" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2.5 rounded-lg text-sm font-bold transition shadow-sm border border-gray-300">
                    Create Custom Run
                </button>
                <button @click="showSubmitModal = true" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg text-sm font-bold transition shadow-sm">
                    Submit Payout
                </button>
            </div>
        </div>

        @if(empty($activeTab) || $activeTab === 'overview' || $activeTab === 'available')
        <!-- Dashboard Home -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Total Earnings</p>
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($stats['total_earnings'] ?? 0) }} <span class="text-red-500 text-lg">G</span></h3>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Active Runs</p>
                <h3 class="text-3xl font-black text-gray-900">{{ $stats['active_runs'] ?? 0 }}</h3>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Completion Rate</p>
                <h3 class="text-3xl font-black text-gray-900">{{ $stats['completion_rate'] ?? 0 }}<span class="text-gray-400 text-lg">%</span></h3>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Latest Available Runs</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">Title</th>
                            <th class="px-6 py-4">Instance / Diff</th>
                            <th class="px-6 py-4">Payout</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($availableRaids as $raid)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-bold text-gray-900">{{ $raid->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $raid->instance_name }} <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-200 text-gray-700 ml-2 uppercase">{{ $raid->difficulty }}</span></td>
                            <td class="px-6 py-4 text-sm font-bold text-emerald-600">{{ number_format($raid->price_per_spot) }} G</td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="takeSpot({{ $raid->id }})" class="bg-gray-900 hover:bg-gray-800 text-white text-[11px] font-bold uppercase tracking-widest px-4 py-2 rounded-lg transition shadow-sm">
                                    Apply
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500 font-medium bg-gray-50/30">No available runs at the moment. Check back later.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if(in_array($activeTab, ['full_raids', 'curves', 'mythic_raids', 'legacy', 'all_raids']))
        <!-- Bookings Sub-Tabs -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 capitalize">{{ str_replace('_', ' ', $activeTab) }} listings</h3>
                <span class="text-xs font-bold bg-red-100 text-red-700 px-3 py-1 rounded-full">{{ count($myBoosts) }} Active Selected</span>
            </div>
            <div class="p-6">
                <!-- Grid of runs matching this category (mocked gracefully) -->
                @if(count($myBoosts) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($myBoosts as $boost)
                    <div class="border border-gray-200 rounded-xl p-5 hover:shadow-md transition bg-white relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-red-500"></div>
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="text-gray-900 font-bold">{{ $boost->event->title ?? 'N/A' }}</h4>
                                <span class="text-xs font-bold text-gray-500 mt-1 block">{{ $boost->event->scheduled_at?->format('d M — H:i') }}</span>
                            </div>
                            <span class="bg-gray-100 text-gray-700 text-[10px] font-extrabold px-2 py-1 rounded uppercase tracking-wider">{{ $boost->role }}</span>
                        </div>
                        <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-100">
                            <button class="text-xs font-bold text-red-600 hover:text-red-700 uppercase tracking-widest transition">Copy Info</button>
                            <span class="text-xs text-emerald-600 font-bold uppercase tracking-widest flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                Assigned
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-16">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <p class="text-gray-500 font-medium">No active bookings found in this category.</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        @if(in_array($activeTab, ['balance', 'deposits', 'web_bookings', 'gold_collection']))
        <!-- Finance Sub-Tabs -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900 capitalize">{{ str_replace('_', ' ', $activeTab) }} Overview</h3>
            </div>
            <div class="p-8 text-center max-w-2xl mx-auto">
                <svg class="w-16 h-16 text-emerald-500/20 mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-11v6h2v-6h-2zm0-4v2h2V7h-2z"/></svg>
                <div class="text-5xl font-black text-gray-900 mb-2">{{ number_format(auth()->user()->balance()) }} <span class="text-emerald-500 text-2xl">G</span></div>
                <div class="text-xs text-gray-500 uppercase tracking-widest font-bold mt-4">Calculated Total Available</div>
                
                <div class="mt-8 p-6 bg-gray-50 rounded-xl border border-gray-200">
                    <p class="text-sm font-medium text-gray-600">Your recent transactions and ledger history are verified and up to date.</p>
                </div>
            </div>
        </div>
        @endif

    </div>

    <!-- Modals remain dark-styled based on standard patterns or could be light. Using light/dark mix for modals -->
    <div x-show="showCreateModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4 shadow-2xl" x-cloak x-transition>
        <div @click="showCreateModal = false" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-lg bg-white rounded-2xl p-8 shadow-2xl border border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Create New Custom Run</h2>
            <form wire:submit.prevent="createNewRaid" class="space-y-4">
                <input type="text" wire:model="title" placeholder="Raid Title (e.g. Heroic Vault)" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-900 font-medium focus:border-red-500 outline-none">
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" wire:model="instance" placeholder="Instance" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-900 font-medium focus:border-red-500 outline-none">
                    <select wire:model="difficulty" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-900 font-medium focus:border-red-500 outline-none">
                        <option value="">Difficulty</option>
                        <option value="Normal">Normal</option>
                        <option value="Heroic">Heroic</option>
                        <option value="Mythic">Mythic</option>
                    </select>
                </div>
                <input type="datetime-local" wire:model="scheduled_at" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-900 font-medium focus:border-red-500 outline-none">
                <input type="number" wire:model="price" placeholder="Price per Spot (Gold)" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-900 font-medium focus:border-red-500 outline-none">
                <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-bold py-3 pt-3.5 rounded-lg text-sm transition uppercase tracking-widest mt-2 shadow-md">
                    Initialize Run
                </button>
            </form>
        </div>
    </div>

    <!-- Completion Modal -->
    <div x-show="showSubmitModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4 shadow-2xl" x-cloak x-transition>
        <div @click="showSubmitModal = false" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-lg bg-white rounded-2xl p-8 shadow-2xl border border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Submit Run Completion</h2>
            <form wire:submit.prevent="submitCompletion" class="space-y-4">
                <select wire:model="submissionRaidId" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-900 font-medium focus:border-red-500 outline-none">
                    <option value="">Select Completed Raid...</option>
                    @foreach($ledRaids as $r)
                    <option value="{{ $r->id }}">{{ $r->title }}</option>
                    @endforeach
                </select>
                <input type="text" wire:model="submissionProof" placeholder="Proof Link (e.g. imgur.com/...)" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-900 font-medium focus:border-red-500 outline-none">
                <input type="number" wire:model="goldPot" placeholder="Total Gold Collected" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-900 font-medium focus:border-red-500 outline-none">
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 pt-3.5 rounded-lg text-sm transition uppercase tracking-widest mt-2 shadow-md">
                    Confirm & Distribute Gold
                </button>
            </form>
        </div>
    </div>
</div>
