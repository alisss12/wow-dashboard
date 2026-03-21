<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Donation Shop') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Balances Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-bold mb-1">Donation & Vote Shop</h3>
                    <p class="text-sm text-gray-500 mb-2">Exchange your points for exclusive in-game digital goods.</p>
                    
                    <div class="mt-4 flex space-x-6">
                        <div class="text-center bg-gray-50 px-4 py-2 rounded-lg border">
                            <span class="block text-xl font-bold text-indigo-600">{{ auth()->user()->vote_points }} VP</span>
                        </div>
                        <div class="text-center bg-gray-50 px-4 py-2 rounded-lg border">
                            <span class="block text-xl font-bold text-yellow-600">{{ auth()->user()->donation_points }} DP</span>
                        </div>
                    </div>
                </div>

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-6 mt-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                
                <div class="p-6">
                    @if(isset($categories) && count($categories) > 0)
                        @foreach($categories as $category)
                            <div class="mb-10 last:mb-0">
                                <h4 class="text-xl font-bold text-gray-800 border-b pb-2 mb-6">{{ $category->name }}</h4>
                                
                                @if(isset($itemsByCategory[$category->id]) && count($itemsByCategory[$category->id]) > 0)
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                        @foreach($itemsByCategory[$category->id] as $item)
                                            <div class="border border-gray-200 rounded-lg p-4 flex flex-col hover:shadow-md transition">
                                                <div class="h-24 w-full bg-gray-100 rounded flex items-center justify-center mb-4">
                                                    @if($item->image)
                                                        <img src="{{ $item->image }}" alt="{{ $item->name }}" class="max-h-20 max-w-full">
                                                    @else
                                                        <span class="text-gray-400 font-bold uppercase tracking-wider text-sm">{{ $item->type }}</span>
                                                    @endif
                                                </div>
                                                
                                                <h5 class="font-bold text-gray-900 mb-1 truncate">{{ $item->name }}</h5>
                                                <p class="text-xs text-gray-500 mb-4 h-8 overflow-hidden">{{ Str::limit($item->description, 60) }}</p>
                                                
                                                <div class="mt-auto space-y-3">
                                                    @if($item->price_vote > 0)
                                                        <form action="{{ route('shop.purchase', $item->id) }}" method="POST" class="w-full space-y-2">
                                                            @csrf
                                                            <input type="hidden" name="currency" value="vote">
                                                            <div>
                                                                <input type="text" name="character_name" required placeholder="Character Name" class="w-full text-sm border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 py-1.5 px-2">
                                                            </div>
                                                            <button type="submit" class="w-full bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 font-semibold py-1.5 px-3 rounded text-sm transition">
                                                                Buy for {{ $item->price_vote }} VP
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    @if($item->price_donate > 0)
                                                        @if($item->price_vote > 0) <hr class="border-gray-100 my-1"> @endif
                                                        <form action="{{ route('shop.purchase', $item->id) }}" method="POST" class="w-full space-y-2">
                                                            @csrf
                                                            <input type="hidden" name="currency" value="donate">
                                                            <div>
                                                                <input type="text" name="character_name" required placeholder="Character Name" class="w-full text-sm border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 py-1.5 px-2">
                                                            </div>
                                                            <button type="submit" class="w-full bg-yellow-50 hover:bg-yellow-100 text-yellow-700 border border-yellow-200 font-semibold py-1.5 px-3 rounded text-sm transition">
                                                                Buy for {{ $item->price_donate }} DP
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500 italic">No items available in this category.</p>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-gray-500 py-8">
                            The shop is currently empty.
                            Please check back later or add items via the admin dashboard.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
