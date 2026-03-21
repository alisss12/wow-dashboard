<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vote for Rewards') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-bold mb-1">Available Voting Sites</h3>
                    <p class="text-sm text-gray-500 mb-6">Vote on these sites every 12-24 hours to earn Vote Points for the shop. You currently have <strong class="text-indigo-600">{{ auth()->user()->vote_points }} VP</strong>.</p>
                </div>

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-6 mt-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                
                <div class="p-6">
                    @if(isset($sites) && count($sites) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($sites as $site)
                                <div class="border rounded-lg p-4 flex flex-col items-center text-center shadow-sm">
                                    <div class="h-16 w-full flex items-center justify-center bg-gray-50 rounded mb-4">
                                        @if($site->image_url)
                                            <img src="{{ $site->image_url }}" alt="{{ $site->name }}" class="max-h-12">
                                        @else
                                            <span class="font-bold text-gray-400 text-xl">{{ $site->name }}</span>
                                        @endif
                                    </div>
                                    <h4 class="font-bold text-gray-800 mb-1">{{ $site->name }}</h4>
                                    <p class="text-xs font-semibold text-indigo-600 mb-4 bg-indigo-50 px-2 py-1 rounded">Rewards {{ $site->reward_points }} VP</p>
                                    
                                    <form action="{{ route('vote.submit', $site->id) }}" method="POST" target="_blank" class="w-full mt-auto" onsubmit="setTimeout(function(){ window.location.reload(); }, 2000);">
                                        @csrf
                                        @if($site->can_vote)
                                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                                                Vote Now
                                            </button>
                                        @else
                                            <button type="button" disabled class="w-full bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded cursor-not-allowed">
                                                Available {{ $site->next_vote_at->diffForHumans() }}
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-gray-500 py-8">
                            No active voting sites currently configured. 
                            Please check back later or configure them in the admin dashboard.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
