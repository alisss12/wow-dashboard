<nav class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-[#1e1e1e] bg-[#222222] px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
    <button type="button" @click="mobileMenuOpen = true" class="-m-2.5 p-2.5 text-slate-400 lg:hidden">
        <span class="sr-only">Open sidebar</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
    </button>

    <div class="h-6 w-px bg-slate-800 lg:hidden" aria-hidden="true"></div>

    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6 items-center justify-end">
        <div class="flex items-center gap-x-4 lg:gap-x-6">
            <!-- Balance Pill -->
            <livewire:balance-card />

            <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-slate-800" aria-hidden="true"></div>

            <!-- User Menu -->
            <div class="relative">
                <div class="flex items-center gap-x-4 p-1.5 focus:outline-none">
                    <img class="h-8 w-8 rounded-full bg-slate-800" src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}" alt="">
                    <span class="hidden lg:flex lg:items-center">
                        <span class="ml-4 text-sm font-semibold leading-6 text-white" aria-hidden="true">{{ auth()->user()->discord_name ?? auth()->user()->name }}</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</nav>
