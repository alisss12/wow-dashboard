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

        <div class="bg-[#222] border border-gray-800 rounded-xl shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-gray-800 bg-[#2b2b2b]">
                <h3 class="text-lg font-bold text-gray-100">Available Posted Runs</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-gray-900 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-800">
                        <tr>
                            <th class="px-6 py-4">Time</th>
                            <th class="px-6 py-4">Raid Name</th>
                            <th class="px-6 py-4 text-center">VIP</th>
                            <th class="px-6 py-4">Difficulty</th>
                            <th class="px-6 py-4">Progress</th>
                            <th class="px-6 py-4">Client</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800 bg-[#222]">
                        @forelse($availableRaids as $raid)
                        <tr class="hover:bg-[#2a2a2a] transition group">
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-emerald-500">{{ $raid->scheduled_at ? \Carbon\Carbon::parse($raid->scheduled_at)->format('d M — H:i') : 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-purple-400">{{ $raid->title }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <svg class="w-4 h-4 text-gray-500 mx-auto" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15 8H9L12 2Z M4 12L8 9V15L4 12Z M20 12L16 15V9L20 12Z"/></svg>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-300">{{ $raid->difficulty }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-300">0/6</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-100">{{ $raid->buyer_name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-xs font-bold bg-emerald-500/20 text-emerald-400">Available</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="takeSpot({{ $raid->id }})" class="bg-[#1e1e1e] border border-gray-700 hover:bg-emerald-600 hover:text-white hover:border-emerald-500 text-gray-300 text-[11px] font-bold uppercase tracking-widest px-4 py-2 rounded-lg transition shadow-sm">
                                    Take Spot
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500 font-medium bg-[#222]">No available runs at the moment. Check back later.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if(in_array($activeTab, ['full_raids', 'curves', 'mythic_raids', 'legacy', 'all_raids']))
        <!-- My Active Runs (Booster) -->
        <div class="bg-[#222] border border-gray-800 rounded-xl shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-gray-800 bg-[#2b2b2b] flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-100 capitalize">My Active Runs</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-gray-900 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-800">
                        <tr>
                            <th class="px-6 py-4">Time</th>
                            <th class="px-6 py-4">Raid Name</th>
                            <th class="px-6 py-4 text-center">VIP</th>
                            <th class="px-6 py-4">Difficulty</th>
                            <th class="px-6 py-4">Progress</th>
                            <th class="px-6 py-4">Leader</th>
                            <th class="px-6 py-4">Client</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800 bg-[#222]">
                        @forelse($myBoosts as $boost)
                        <tr class="hover:bg-[#2a2a2a] transition group">
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-emerald-500">{{ $boost->event->scheduled_at?->format('d M — H:i') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-purple-400">{{ $boost->event->title ?? 'N/A' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <svg class="w-4 h-4 text-gray-500 mx-auto" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15 8H9L12 2Z M4 12L8 9V15L4 12Z M20 12L16 15V9L20 12Z"/></svg>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-300">{{ $boost->event->difficulty ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-300">0/6</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-300">{{ $boost->event->leader_name ?? 'TBD' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-100">{{ $boost->event->buyer_name ?? 'Client' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if(($boost->event->status ?? '') === 'locked')
                                    <span class="px-2 py-1 rounded text-xs font-bold bg-orange-500/20 text-orange-400">Locked</span>
                                @elseif(($boost->event->status ?? '') === 'completed')
                                    <span class="px-2 py-1 rounded text-xs font-bold bg-emerald-500/20 text-emerald-400">Completed</span>
                                @else
                                    <span class="px-2 py-1 rounded text-xs font-bold bg-emerald-500/20 text-emerald-400">Active</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500 font-medium bg-[#222]">No active runs assigned to you yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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

    <div x-show="showCreateModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4 shadow-2xl" x-cloak x-transition>
        <div @click="showCreateModal = false" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-2xl p-8 shadow-2xl border border-gray-200 max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Create New Custom Run</h2>
            <form wire:submit.prevent="createNewRaid" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Row 1 -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1 uppercase tracking-wider">Raid Type</label>
                    <select wire:model="raid_type" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-900 font-medium focus:border-red-500 outline-none">
                        <option value="">Select Type</option>
                        <option value="Full Run">Full Run</option>
                        <option value="Curve">Curve</option>
                        <option value="Mythic Raid">Mythic Raid</option>
                        <option value="Legacy">Legacy</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1 uppercase tracking-wider">Difficulty</label>
                    <select wire:model="difficulty" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-900 font-medium focus:border-red-500 outline-none">
                        <option value="">Select Difficulty</option>
                        <option value="Normal">Normal</option>
                        <option value="Heroic">Heroic</option>
                        <option value="Mythic">Mythic</option>
                    </select>
                </div>

                <!-- Row 2 -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 mb-1 uppercase tracking-wider">Scheduled Time</label>
                    <input type="datetime-local" wire:model="scheduled_at" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-900 font-medium focus:border-red-500 outline-none">
                </div>

                <!-- Row 3 -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1 uppercase tracking-wider">Group Type</label>
                    <select wire:model="group_type" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-900 font-medium focus:border-red-500 outline-none">
                        <option value="">Select Group</option>
                        <option value="Full Run">Full Run</option>
                        <option value="Partial">Partial</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1 uppercase tracking-wider">Loot Type</label>
                    <select wire:model="loot_type" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-900 font-medium focus:border-red-500 outline-none">
                        <option value="">Select Loot</option>
                        <option value="Saved">Saved</option>
                        <option value="Personal">Personal</option>
                        <option value="Unsaved">Unsaved</option>
                    </select>
                </div>

                <!-- Finances -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 mb-1 uppercase tracking-wider">Base Pot Size (Gold)</label>
                    <input type="number" wire:model="pot_size" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-900 font-medium focus:border-red-500 outline-none" min="0">
                </div>

                <div class="md:col-span-2 mt-4">
                    <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-bold py-3 pt-3.5 rounded-lg text-sm transition uppercase tracking-widest shadow-md">
                        Initialize Run
                    </button>
                </div>
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
