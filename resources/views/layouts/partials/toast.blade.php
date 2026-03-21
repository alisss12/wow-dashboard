<div x-data="{ 
        toasts: [],
        add(toast) {
            toast.id = Date.now();
            this.toasts.push(toast);
            setTimeout(() => this.remove(toast.id), 5000);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }"
    @notify.window="add($event.detail)"
    class="fixed bottom-4 right-4 z-50 flex flex-col gap-2"
    x-cloak>
    
    <template x-for="toast in toasts" :key="toast.id">
        <div class="bg-slate-800 text-white px-4 py-3 rounded-lg shadow-lg border border-slate-700 flex items-center gap-3 min-w-[200px]"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <div :class="{
                'text-indigo-500': !toast.type || toast.type === 'info',
                'text-emerald-500': toast.type === 'success',
                'text-rose-500': toast.type === 'error',
                'text-amber-500': toast.type === 'warning'
            }">
                <svg x-show="toast.type === 'success'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <svg x-show="toast.type === 'error'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <svg x-show="!toast.type || toast.type === 'info' || toast.type === 'warning'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            
            <div class="flex-1">
                <p class="text-xs font-bold" x-text="toast.message"></p>
            </div>

            <button @click="remove(toast.id)" class="text-slate-400 hover:text-white">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </template>
</div>
