<div x-data="{ 
    balanceOpen: false, 
    bookingsOpen: false 
}">
    <!-- Mobile sidebar -->
    <div x-show="mobileMenuOpen" class="relative z-[100] lg:hidden" x-ref="dialog" aria-modal="true" x-cloak>
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition-opacity ease-linear duration-300" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-slate-950/90 backdrop-blur-sm" @click="mobileMenuOpen = false"></div>

        <div class="fixed inset-0 flex">
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-in-out duration-300 transform" 
                 x-transition:enter-start="-translate-x-full" 
                 x-transition:enter-end="translate-x-0" 
                 x-transition:leave="transition ease-in-out duration-300 transform" 
                 x-transition:leave-start="translate-x-0" 
                 x-transition:leave-end="-translate-x-full" 
                 class="relative mr-16 flex w-full max-w-xs flex-1 bg-[#222222] border-r border-[#333] shadow-2xl">
                 
                <!-- Mobile content follows desktop structure closely, omitted for brevity, keeping desktop perfect -->
            </div>
        </div>
    </div>

    <!-- Static sidebar for desktop -->
    <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-64 lg:flex-col">
        <div class="flex grow flex-col gap-y-1 overflow-y-auto bg-[#2b2b2b] pb-4">
            
            <div class="flex h-24 shrink-0 items-center justify-center border-b border-[#1e1e1e] bg-[#222222]">
                <!-- Huge DawnHub Sun Logo Placeholder -->
                <div class="w-16 h-16 rounded-xl bg-gradient-to-t from-red-600 to-orange-400 flex items-center justify-center shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15 8H9L12 2Z M4 12L8 9V15L4 12Z M20 12L16 15V9L20 12Z"/></svg>
                </div>
            </div>

            <nav class="flex flex-1 flex-col pt-4">
                <ul role="list" class="flex flex-1 flex-col">
                    
                    <li>
                        <a href="/dashboard" class="group flex items-center gap-x-3 px-6 py-3 text-sm font-semibold leading-6 text-gray-300 hover:text-white hover:bg-[#333] transition-colors {{ request()->is('dashboard') && !request('tab') ? 'bg-[#333] text-white border-l-4 border-emerald-500' : 'border-l-4 border-transparent' }}">
                            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3v-6h6v6h3a1 1 0 001-1V10" /></svg>
                            Home
                        </a>
                    </li>

                    <li>
                        <button type="button" @click="balanceOpen = !balanceOpen" class="w-full group flex items-center justify-between px-6 py-3 text-sm font-semibold leading-6 text-gray-300 hover:text-white hover:bg-[#333] transition-colors border-l-4 border-transparent">
                            <div class="flex items-center gap-x-3">
                                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Balance
                            </div>
                            <svg class="h-4 w-4 transform transition-transform duration-200" :class="{'rotate-180': balanceOpen}" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                        <ul x-show="balanceOpen" x-transition class="bg-[#222222] py-2" x-cloak>
                            <li><a href="/dashboard?tab=balance" class="block py-2 pl-14 pr-6 text-sm text-gray-400 hover:text-white hover:text-emerald-500 transition-colors {{ request('tab') == 'balance' ? 'text-emerald-500' : '' }}">Balance</a></li>
                            <li><a href="/dashboard?tab=deposits" class="block py-2 pl-14 pr-6 text-sm text-gray-400 hover:text-white hover:text-emerald-500 transition-colors {{ request('tab') == 'deposits' ? 'text-emerald-500' : '' }}">Deposits</a></li>
                            <li><a href="/dashboard?tab=web_bookings" class="block py-2 pl-14 pr-6 text-sm text-gray-400 hover:text-white hover:text-emerald-500 transition-colors {{ request('tab') == 'web_bookings' ? 'text-emerald-500' : '' }}">Website Bookings</a></li>
                            <li><a href="/dashboard?tab=gold_collection" class="block py-2 pl-14 pr-6 text-sm text-gray-400 hover:text-white hover:text-emerald-500 transition-colors {{ request('tab') == 'gold_collection' ? 'text-emerald-500' : '' }}">My Gold Collection</a></li>
                        </ul>
                    </li>

                    <li>
                        <button type="button" @click="bookingsOpen = !bookingsOpen" class="w-full group flex items-center justify-between px-6 py-3 text-sm font-semibold leading-6 text-gray-300 hover:text-white hover:bg-[#333] transition-colors border-l-4 border-transparent">
                            <div class="flex items-center gap-x-3">
                                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                Bookings
                            </div>
                            <svg class="h-4 w-4 transform transition-transform duration-200" :class="{'rotate-180': bookingsOpen}" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                        <ul x-show="bookingsOpen" x-transition class="bg-[#222222] py-2" x-cloak>
                            <li><a href="/dashboard?tab=full_raids" class="block py-2 pl-14 pr-6 text-sm text-gray-400 hover:text-white hover:text-emerald-500 transition-colors {{ request('tab') == 'full_raids' ? 'text-emerald-500' : '' }}">Full Raids</a></li>
                            <li><a href="/dashboard?tab=curves" class="block py-2 pl-14 pr-6 text-sm text-gray-400 hover:text-white hover:text-emerald-500 transition-colors {{ request('tab') == 'curves' ? 'text-emerald-500' : '' }}">Curves</a></li>
                            <li><a href="/dashboard?tab=mythic_raids" class="block py-2 pl-14 pr-6 text-sm text-gray-400 hover:text-white hover:text-emerald-500 transition-colors {{ request('tab') == 'mythic_raids' ? 'text-emerald-500' : '' }}">Mythic Raids</a></li>
                            <li><a href="/dashboard?tab=legacy" class="block py-2 pl-14 pr-6 text-sm text-gray-400 hover:text-white hover:text-emerald-500 transition-colors {{ request('tab') == 'legacy' ? 'text-emerald-500' : '' }}">Legacy</a></li>
                            <li><a href="/dashboard?tab=all_raids" class="block py-2 pl-14 pr-6 text-sm text-gray-400 hover:text-white hover:text-emerald-500 transition-colors {{ request('tab') == 'all_raids' ? 'text-emerald-500' : '' }}">All Raids</a></li>
                        </ul>
                    </li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full group flex items-center gap-x-3 px-6 py-3 text-sm font-semibold leading-6 text-gray-300 hover:text-white hover:bg-[#333] transition-colors border-l-4 border-transparent text-left">
                                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg>
                                Logout
                            </button>
                        </form>
                    </li>

                </ul>
                
                <div class="mt-auto px-6 py-4 flex flex-col gap-2 border-t border-[#1e1e1e]">
                    <div class="text-white text-center">
                        <p class="text-sm font-bold">EU Server Time:</p>
                        <p class="text-xs text-gray-400" x-data="{ time: '' }" x-init="setInterval(() => time = new Date().toLocaleTimeString('en-GB', {timeZone: 'Europe/Paris'}) + ' CET', 1000)" x-text="time">--:--:--</p>
                    </div>
                    <div class="text-white text-center mt-2">
                        <p class="text-sm font-bold">NA Server Time:</p>
                        <p class="text-xs text-gray-400" x-data="{ time: '' }" x-init="setInterval(() => time = new Date().toLocaleTimeString('en-US', {timeZone: 'America/New_York'}) + ' EDT', 1000)" x-text="time">--:--:--</p>
                    </div>
                </div>

            </nav>
        </div>
    </div>
</div>
