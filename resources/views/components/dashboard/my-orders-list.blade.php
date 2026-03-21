@props(['mySignups'])

<div class="card-dark rounded-2xl overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-900 border-b border-gray-800 text-[9px] font-black text-gray-500 uppercase font-mono">
            <tr>
                <th class="px-6 py-3">Mission / Client</th>
                <th class="px-6 py-3">Character</th>
                <th class="px-6 py-3">Price</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Progress</th>
                @if(in_array(auth()->user()->account_type, ['admin', 'staff']))
                    <th class="px-6 py-3">Advertiser</th>
                @endif
                <th class="px-6 py-3 text-right">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
            @forelse($mySignups->where('is_booster', false) as $booking)
                <tr class="hover:bg-white/5 transition">
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-xs font-black text-white italic tracking-tight">{{ $booking->event->title ?? 'Custom/M+ Request' }}</span>
                            <span class="text-[8px] text-gray-500 font-bold uppercase tracking-widest">{{ $booking->event->scheduled_at->format('M j, H:i') ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-gray-300">{{ $booking->character_name }}</span>
                            <span class="text-[9px] font-bold text-gray-600 uppercase">{{ $booking->buyer_realm }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-xs font-bold">
                        <span class="text-indigo-400">{{ $booking->class }}</span>
                        <span class="text-gray-500 ml-1">({{ $booking->spec }})</span>
                    </td>
                    <td class="px-6 py-4 text-xs font-mono text-green-500">{{ number_format($booking->agreed_price) }}g</td>
                    <td class="px-6 py-4" colspan="2">
                        <x-dashboard.status-timeline :status="$booking->event->status ?? 'queued'" :isQueue="$booking->event->is_queue ?? false" />
                    </td>
                    @if(in_array(auth()->user()->account_type, ['admin', 'staff']))
                        <td class="px-6 py-4 text-[9px] font-black text-indigo-400 uppercase tracking-tighter">
                            {{ $booking->advertiser->name ?? 'System' }}
                        </td>
                    @endif
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-3">
                            @php 
                                $raidStatus = $booking->event->status ?? 'unknown';
                                $isLocked = in_array($raidStatus, ['running', 'completed', 'cancelled']) || 
                                           (!in_array(auth()->user()->account_type, ['admin', 'staff']) && $booking->event && now()->greaterThanOrEqualTo($booking->event->scheduled_at->copy()->subMinutes(30)));
                            @endphp

                            @if(!$isLocked)
                                <button @click="$dispatch('open-edit-order-modal', { 
                                    id: {{ $booking->id }}, 
                                    char: '{{ $booking->character_name }}',
                                    realm: '{{ $booking->buyer_realm }}',
                                    faction: '{{ $booking->buyer_faction }}',
                                    role: '{{ $booking->role }}',
                                    class: '{{ $booking->class }}',
                                    spec: '{{ $booking->spec }}',
                                    armor: '{{ $booking->armor_type }}',
                                    price: {{ $booking->agreed_price }},
                                    discord: '{{ $booking->client_discord }}',
                                    priority: '{{ $booking->loot_priority }}',
                                    notes: '{{ $booking->notes }}'
                                })" class="text-[9px] font-black text-indigo-400 uppercase hover:text-indigo-300">Edit</button>

                                <form action="{{ route('signups.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Abort this booking protocol?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[9px] font-black text-red-500 uppercase hover:text-red-400">Cancel</button>
                                </form>
                            @else
                                <span class="text-[8px] font-black text-gray-700 uppercase italic" title="Locked: Within 30m of deployment or run active.">Locked</span>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="{{ in_array(auth()->user()->account_type, ['admin', 'staff']) ? 8 : 7 }}" class="p-10 text-center text-[10px] text-gray-600 uppercase font-black">No raid bookings found. Visit the marketplace to start.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
