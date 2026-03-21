<div class="space-y-10 pb-20" x-data="{ showCreateModal: @entangle('showCreateModal') }">
    <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-white tracking-tighter uppercase italic">Mission <span class="text-amber-500 underline decoration-amber-500/30 underline-offset-8">Control</span></h1>
            <p class="text-slate-500 mt-2 font-bold uppercase tracking-[0.3em] text-[10px] flex items-center">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2"></span>
                Operation Management Cluster Active
            </p>
        </div>
        <button @click="showCreateModal = true" class="group relative px-8 py-4 bg-amber-600 hover:bg-amber-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-xl shadow-amber-500/20">
            Deploy Tactical Op
        </button>
    </div>

    @livewire('balance-card', ['sharedData' => $sharedData])

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-10">
        <div class="xl:col-span-2 space-y-6">
            <h3 class="text-[10px] font-black text-white uppercase tracking-[0.4em] px-4 italic">Deployment Manifest</h3>
            <div class="glass-card border border-white/5 rounded-[2rem] overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-white/2 border-b border-white/5">
                        <tr>
                            <th class="px-8 py-5 text-[9px] font-black text-slate-500 uppercase tracking-widest">Operation</th>
                            <th class="px-8 py-5 text-[9px] font-black text-slate-500 uppercase tracking-widest text-center">Status</th>
                            <th class="px-8 py-5 text-[9px] font-black text-slate-500 uppercase tracking-widest text-right">Payload</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($ledRaids as $raid)
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="px-8 py-6">
                                <p class="text-xs font-black text-white uppercase tracking-tighter">{{ $raid->title }}</p>
                                <p class="text-[9px] font-black text-slate-500 uppercase mt-0.5">{{ $raid->instance_name }} • {{ $raid->difficulty }}</p>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="px-2 py-0.5 bg-amber-500/10 text-amber-500 text-[8px] font-black rounded uppercase tracking-widest border border-amber-500/20">{{ $raid->status }}</span>
                            </td>
                            <td class="px-8 py-6 text-right text-xs font-black text-white font-mono italic">{{ number_format($raid->price_per_spot) }}G</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-8 py-10 text-center text-[10px] text-slate-600 font-black italic uppercase">No ops in pipeline.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <h3 class="text-[10px] font-black text-white uppercase tracking-[0.4em] px-4 italic">Squad Applications</h3>
            <div class="space-y-4">
                @forelse($pendingSignups as $signup)
                <div class="glass-card border border-white/5 rounded-2xl p-6 group hover:border-amber-500/20 transition">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-black text-white tracking-tighter">{{ $signup->character_name }}</span>
                        <span class="text-[8px] font-black text-amber-500 uppercase tracking-widest">{{ $signup->role }}</span>
                    </div>
                    <p class="text-[9px] font-black text-slate-500 uppercase mb-4">{{ $signup->event->title ?? 'Raid' }}</p>
                    <button wire:click="approveSignup({{ $signup->id }})" class="w-full py-2.5 bg-slate-900 border border-white/10 rounded-lg text-[9px] font-black text-white uppercase tracking-widest hover:bg-emerald-600 transition">Authorize Operative</button>
                </div>
                @empty
                <div class="p-8 text-center glass-card border-dashed border-white/5 rounded-2xl">
                    <p class="text-[9px] font-black text-slate-600 uppercase italic">Intel channel clear.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div x-show="showCreateModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6" x-cloak x-transition>
        <div @click="showCreateModal = false" class="absolute inset-0 bg-[#020617]/95 backdrop-blur-xl"></div>
        <div class="relative w-full max-w-2xl bg-slate-900 border border-white/10 rounded-[2.5rem] p-10 shadow-3xl">
            <h2 class="text-3xl font-black text-white tracking-tighter uppercase italic mb-8">Deploy <span class="text-amber-500">Operation</span></h2>
            <form wire:submit.prevent="createRaid" class="grid grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-3 block ml-1">Mission Codename</label>
                    <input type="text" wire:model="title" class="w-full bg-slate-950 border border-white/10 rounded-xl px-5 py-4 text-xs font-black text-white focus:border-amber-500 outline-none">
                </div>
                <div>
                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-3 block ml-1">Sector (Instance)</label>
                    <input type="text" wire:model="instance" class="w-full bg-slate-950 border border-white/10 rounded-xl px-5 py-4 text-xs font-black text-white focus:border-amber-500 outline-none">
                </div>
                <div>
                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-3 block ml-1">Difficulty Class</label>
                    <select wire:model="difficulty" class="w-full bg-slate-950 border border-white/10 rounded-xl px-5 py-4 text-xs font-black text-white focus:border-amber-500 outline-none">
                        <option value="Normal">Normal</option>
                        <option value="Heroic">Heroic</option>
                        <option value="Mythic">Mythic</option>
                    </select>
                </div>
                <div>
                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-3 block ml-1">Sync Date/Time</label>
                    <input type="datetime-local" wire:model="scheduled_at" class="w-full bg-slate-950 border border-white/10 rounded-xl px-5 py-4 text-xs font-black text-white">
                </div>
                <div>
                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-3 block ml-1">Payload (Gold)</label>
                    <input type="number" wire:model="price" class="w-full bg-slate-950 border border-white/10 rounded-xl px-5 py-4 text-xs font-black text-white">
                </div>
                <button type="submit" class="col-span-2 py-5 bg-amber-600 hover:bg-amber-500 text-white rounded-xl text-[11px] font-black uppercase tracking-[0.2em] shadow-2xl shadow-amber-600/20 transition">Initialize Deployment</button>
            </form>
        </div>
    </div>
</div>
