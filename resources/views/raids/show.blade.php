<x-app-layout>
    <style>
        .page-dark { background-color: #0f111a; color: #9ca3af; }
        .card-dark { background-color: #161922; border: 1px solid #1f2937; }
        .label-dark { color: #4b5563; font-weight: 900; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.15em; }
        .input-dark { background-color: #0c0e14; border: 1px solid #1f2937; color: #f3f4f6; }
        .input-dark:focus { border-color: #6366f1; outline: none; }
    </style>

    <div class="min-h-screen page-dark">
        <!-- HEADER STRIP -->
        <div class="bg-[#161922] border-b border-gray-800 sticky top-0 z-30 shadow-2xl">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <a href="{{ route('raids.index') }}" class="flex items-center text-gray-500 hover:text-white transition group">
                        <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span class="text-[10px] font-black uppercase tracking-widest">Return to Calendar</span>
                    </a>
                    
                    <div class="text-right">
                        <h2 class="text-xl font-black text-white uppercase italic tracking-tighter">{{ $raid->title }}</h2>
                        <span class="text-[9px] font-bold text-indigo-400 uppercase tracking-widest">{{ $raid->instance_name }} Run</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500/20 text-green-500 px-6 py-4 rounded-2xl mb-8 flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-xs font-black uppercase tracking-widest">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- MAIN CONTENT AREA -->
                <div class="lg:col-span-2 space-y-12">
                    
                    <!-- PROTOCOL SPECS -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="card-dark p-6 rounded-3xl">
                            <span class="label-dark block mb-2">Schedule ({{ $raid->region }})</span>
                            <span class="text-sm font-black text-white uppercase italic">{{ $raid->scheduled_at->format('M j, g:i A') }}</span>
                        </div>
                        <div class="card-dark p-6 rounded-3xl">
                            <span class="label-dark block mb-2">Category</span>
                            <span class="text-sm font-black text-indigo-400 uppercase italic">{{ $raid->service_category }}</span>
                        </div>
                        <div class="card-dark p-6 rounded-3xl">
                            <span class="label-dark block mb-2">Squad Leader</span>
                            <span class="text-sm font-black text-white uppercase italic">{{ $raid->assignedLeader->name ?? $raid->booster->name ?? 'SCALING...' }}</span>
                        </div>
                        <div class="card-dark p-6 rounded-3xl">
                            <span class="label-dark block mb-2">Advertiser Intel</span>
                            @php
                                $isAssigned = (auth()->check() && (auth()->user()->account_type === 'admin' || auth()->id() === $raid->booster_user_id || auth()->id() === $raid->assigned_leader_id));
                            @endphp
                            @if($isAssigned)
                                <span class="text-sm font-black text-green-500 uppercase italic">{{ $raid->coordinator_discord ?: ($raid->advertiser->name ?? 'SECURED') }}</span>
                            @else
                                <span class="text-[9px] font-black text-gray-600 uppercase italic tracking-widest">Restricted</span>
                            @endif
                        </div>
                    </div>

                    <!-- INTELLIGENCE BRIEFING -->
                    @if($raid->description)
                    <div class="card-dark p-8 rounded-3xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-5">
                            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                        </div>
                        <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-6 border-b border-gray-800 pb-4">Raid Description</h4>
                        <div class="text-sm text-gray-400 leading-relaxed font-medium">
                            {!! nl2br(e($raid->description)) !!}
                        </div>
                    </div>
                    @endif

                    <!-- DEPLOYED SQUAD (Roster) -->
                    <div class="card-dark p-8 rounded-3xl">
                        <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-8 border-b border-gray-800 pb-4 flex items-center justify-between">
                            <span>Raid Roster</span>
                            <span class="text-indigo-500">{{ $signups->count() }} ACTIVE</span>
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            @foreach(['tank' => 'Shield Units', 'healer' => 'Support Medics', 'mdps' => 'Assault Units (Melee)', 'rdps' => 'Assault Units (Ranged)'] as $roleKey => $roleName)
                            <div class="space-y-4">
                                <h5 class="text-[10px] font-black text-indigo-400 uppercase tracking-tighter flex items-center">
                                    <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full mr-2"></span>
                                    {{ $roleName }}
                                </h5>
                                <div class="space-y-2">
                                    @forelse($roster[$roleKey] ?? [] as $signup)
                                        <div>
                                        <div class="flex items-center">
                                            <span class="text-xs font-black text-gray-200 block uppercase italic">{{ $signup->character_name }}</span>
                                            @if($signup->client?->type === 'vip')
                                                <span class="ml-1 text-yellow-500 text-[10px]" title="VIP Client">⭐</span>
                                            @elseif($signup->client?->type === 'unsafe')
                                                <span class="ml-1 text-red-500 text-[10px]" title="Unsafe Client">⚠️</span>
                                            @endif
                                        </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-[9px] font-bold text-gray-600 uppercase">{{ $signup->class }}</span>
                                                @if($signup->armor_type && $signup->armor_type !== 'N/A')
                                                    <span class="text-[8px] bg-gray-800 text-gray-400 px-1 rounded uppercase font-black">{{ $signup->armor_type }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end">
                                            @if($signup->status === 'accepted')
                                                <span class="text-[8px] font-black text-green-500 uppercase">Confirmed</span>
                                            @else
                                                <span class="text-[8px] font-black text-yellow-500/50 uppercase italic">Awaiting</span>
                                            @endif
                                            @if(auth()->check() && (auth()->user()->account_type === 'admin' || auth()->id() === $raid->booster_user_id))
                                                <div class="flex flex-col items-end mt-1">
                                                    <span class="text-[9px] font-mono text-green-500/80">{{ number_format($signup->agreed_price) }}g</span>
                                                    @if($signup->loot_priority)
                                                        <span class="text-[7px] text-indigo-400 uppercase font-bold">{{ $signup->loot_priority }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @empty
                                    <span class="text-[9px] text-gray-700 font-black uppercase italic tracking-widest block py-2">No units assigned.</span>
                                    @endforelse
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- SIDEBAR: BOOKING SYSTEM -->
                <div class="space-y-6">
                    <div class="card-dark p-8 rounded-3xl sticky top-36 border-indigo-500/10 shadow-2xl shadow-indigo-500/5">
                        <h4 class="text-xs font-black text-white uppercase tracking-widest mb-8 text-center bg-indigo-500/5 py-3 rounded-xl border border-indigo-500/10 italic">Secure Slot Booking</h4>
                        
                        @if(auth()->user()->account_type === 'advertiser' || auth()->user()->account_type === 'admin' || auth()->user()->account_type === 'staff')
                            <!-- Advertiser: Book Client -->
                            @if($raid->status === 'open' || $raid->status === 'approved')
                                <div x-data="{ 
                                    charName: '', 
                                    charRealm: '', 
                                    charClass: 'Warrior', 
                                    charSpec: '',
                                    charArmor: 'Plate',
                                    isScanning: false,
                                    scanError: '',
                                    async scanRaiderIo() {
                                        if(!this.charName || !this.charRealm) {
                                            this.scanError = 'Name & Realm required';
                                            return;
                                        }
                                        this.isScanning = true;
                                        this.scanError = '';
                                        try {
                                            const response = await fetch(`/api/character-lookup?name=${encodeURIComponent(this.charName)}&realm=${encodeURIComponent(this.charRealm)}`);
                                            const data = await response.json();
                                            if(data.success) {
                                                this.charClass = data.class;
                                                this.charSpec = data.spec;
                                                this.charArmor = data.armor_type;
                                            } else {
                                                this.scanError = data.message;
                                            }
                                        } catch (e) {
                                            this.scanError = 'Lookup failed';
                                        } finally {
                                            this.isScanning = false;
                                        }
                                    }
                                }">
                                    
                                    <!-- LOCAL ERROR ALERTS -->
                                    @if(session('error'))
                                        <div class="bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-xl mb-6 text-[10px] font-black uppercase">
                                            {{ session('error') }}
                                        </div>
                                    @endif

                                    @if($errors->any())
                                        <div class="bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-xl mb-6 text-[10px] font-black uppercase">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form action="{{ route('signups.store', $raid->id) }}" method="POST" class="space-y-6">
                                        @csrf
                                        <div class="relative">
                                            <label class="label-dark block mb-2">Subject Character Name</label>
                                            <div class="flex space-x-2">
                                                <input type="text" name="character_name" x-model="charName" required class="input-dark flex-grow rounded-xl px-4 py-3" placeholder="Sylvanas">
                                                <button type="button" @click="scanRaiderIo()" :disabled="isScanning" class="bg-gray-800 hover:bg-gray-700 text-white px-3 rounded-xl transition flex items-center justify-center min-w-[40px]">
                                                    <template x-if="!isScanning">
                                                        <svg class="w-4 h-4" :class="scanError ? 'text-red-500' : 'text-indigo-400'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                                                    </template>
                                                    <template x-if="isScanning">
                                                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                    </template>
                                                </button>
                                            </div>
                                            <template x-if="scanError">
                                                <span class="text-[8px] font-black text-red-500 uppercase mt-1 absolute right-0" x-text="scanError"></span>
                                            </template>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="label-dark block mb-2">Character Realm</label>
                                                <input type="text" name="buyer_realm" x-model="charRealm" class="input-dark w-full rounded-xl px-4 py-3" placeholder="Tarren Mill">
                                            </div>
                                            <div>
                                                <label class="label-dark block mb-2">Faction</label>
                                                <select name="buyer_faction" class="input-dark w-full rounded-xl px-4 py-3 appearance-none">
                                                    <option value="alliance">Alliance</option>
                                                    <option value="horde" selected>Horde</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="label-dark block mb-2">Class</label>
                                                <select name="class" x-model="charClass" required class="input-dark w-full rounded-xl px-4 py-3 appearance-none">
                                                    <option value="Warrior">Warrior</option>
                                                    <option value="Paladin">Paladin</option>
                                                    <option value="Hunter">Hunter</option>
                                                    <option value="Rogue">Rogue</option>
                                                    <option value="Priest">Priest</option>
                                                    <option value="Death Knight">Death Knight</option>
                                                    <option value="Shaman">Shaman</option>
                                                    <option value="Mage">Mage</option>
                                                    <option value="Warlock">Warlock</option>
                                                    <option value="Druid">Druid</option>
                                                    <option value="Monk">Monk</option>
                                                    <option value="Demon Hunter">Demon Hunter</option>
                                                    <option value="Evoker">Evoker</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="label-dark block mb-2">Spec</label>
                                                <input type="text" name="spec" x-model="charSpec" class="input-dark w-full rounded-xl px-4 py-3" placeholder="e.g. Fire">
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="label-dark block mb-2">Target Role</label>
                                                <select name="role" required class="input-dark w-full rounded-xl px-4 py-3 appearance-none">
                                                    <option value="tank">Tank</option>
                                                    <option value="healer">Healer</option>
                                                    <option value="mdps">Melee DPS</option>
                                                    <option value="rdps" selected>Ranged DPS</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="label-dark block mb-2">Armor Type</label>
                                                <select name="armor_type" x-model="charArmor" class="input-dark w-full rounded-xl px-4 py-3 appearance-none">
                                                    <option value="N/A">N/A</option>
                                                    <option value="Plate">Plate</option>
                                                    <option value="Mail">Mail</option>
                                                    <option value="Leather">Leather</option>
                                                    <option value="Cloth">Cloth</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="label-dark block mb-2">Client Discord</label>
                                                <input type="text" name="client_discord" class="input-dark w-full rounded-xl px-4 py-3" placeholder="User#1234">
                                            </div>
                                            <div>
                                                <label class="label-dark block mb-2">Loot Priority</label>
                                                <input type="text" name="loot_priority" class="input-dark w-full rounded-xl px-4 py-3" placeholder="Full Loot">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="label-dark block mb-2">Contract Value (Gold)</label>
                                                <input type="number" name="agreed_price" step="1" class="input-dark w-full rounded-xl px-4 py-3" value="{{ (int)$raid->price_per_spot }}">
                                            </div>
                                            <div>
                                                <label class="label-dark block mb-2">Deposit Paid (Gold)</label>
                                                <input type="number" name="deposit_amount" step="1" class="input-dark w-full rounded-xl px-4 py-3" value="0">
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <label class="label-dark block mb-2">Protocol Notes</label>
                                            <textarea name="notes" rows="2" class="input-dark w-full rounded-xl px-4 py-3" placeholder="Specific requirements..."></textarea>
                                        </div>

                                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition shadow-xl shadow-indigo-600/20 active:scale-95">
                                            Confirm Booking
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="bg-yellow-500/5 border border-yellow-500/10 text-yellow-500 p-6 rounded-3xl text-center">
                                    <span class="text-[10px] font-black uppercase tracking-widest italic">System Offline: Bookings are {{ $raid->status }}</span>
                                </div>
                            @endif
                        @elseif(auth()->user()->account_type === 'booster')
                            <!-- Booster: One-Click Squad Deployment -->
                            <div class="space-y-6">
                                <div class="p-6 bg-indigo-500/5 border border-indigo-500/10 rounded-[2rem] text-center">
                                    <div class="w-16 h-16 bg-indigo-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4 text-indigo-500">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04a11.357 11.357 0 00-1.573 5.374c0 6.574 4.812 12.008 11.191 14.916 6.379-2.908 11.191-8.342 11.191-14.916a11.357 11.357 0 00-1.573-5.374z"></path></svg>
                                    </div>
                                    <h5 class="text-xs font-black text-white uppercase tracking-widest mb-2">Verified Squad Deployment</h5>
                                    <p class="text-[10px] text-gray-500 font-bold uppercase leading-relaxed px-4">Your protocol identity is pre-verified. No further data entry required for this mission.</p>
                                </div>
                                <form action="{{ route('raids.takeSpot', $raid->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white py-5 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] transition shadow-xl shadow-indigo-600/20 active:scale-95">
                                        Deploy to Squad
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="bg-indigo-500/5 border border-indigo-500/10 text-indigo-400 p-6 rounded-3xl text-center">
                                <span class="text-[10px] font-black uppercase tracking-widest leading-relaxed italic">Restricted Access: Mission Deployment Required.</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
