<div class="bg-indigo-600/10 border border-indigo-500/20 rounded-full px-4 py-1.5 flex items-center gap-2">
    <span class="text-indigo-400 font-bold text-sm tracking-tight">{{ number_format($balance) }}<span class="ml-0.5">G</span></span>
    <button wire:click="copyMailString" class="text-indigo-400 hover:text-indigo-300 transition" title="Copy Balance">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
        </svg>
    </button>
</div>
