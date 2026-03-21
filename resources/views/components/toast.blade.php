<div x-data="{ 
        toasts: [],
        add(toast) {
            toast.id = Date.now();
            this.toasts.push(toast);
            setTimeout(() => {
                this.remove(toast.id);
            }, 5000);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }"
    @notify.window="add($event.detail)"
    class="fixed top-5 right-5 z-[100] flex flex-col items-end space-y-4 pointer-events-none"
    x-cloak>
    
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="true" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-x-full opacity-0 scale-95"
             x-transition:enter-end="translate-x-0 opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="translate-x-full opacity-0 scale-90"
             class="pointer-events-auto bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-2xl shadow-2xl p-4 flex items-center space-x-4 min-w-[280px] max-w-sm group border-l-4"
             :class="{
                'border-l-indigo-500': toast.type === 'info' || !toast.type,
                'border-l-emerald-500': toast.type === 'success',
                'border-l-rose-500': toast.type === 'error',
                'border-l-amber-500': toast.type === 'warning'
             }">
            
            <div class="flex-shrink-0">
                <!-- Info Icon -->
                <template x-if="toast.type === 'info' || !toast.type">
                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </template>
                <!-- Success Icon -->
                <template x-if="toast.type === 'success'">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </template>
                <!-- Error Icon -->
                <template x-if="toast.type === 'error'">
                    <svg class="w-6 h-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </template>
                <!-- Warning Icon -->
                <template x-if="toast.type === 'warning'">
                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </template>
            </div>
            
            <div class="flex-1">
                <p class="text-[11px] font-black text-white uppercase tracking-wider mb-0.5" x-text="toast.title || 'System Alert'"></p>
                <p class="text-[10px] font-bold text-slate-400 leading-tight" x-text="toast.message"></p>
            </div>
            
            <button @click="remove(toast.id)" class="text-slate-600 hover:text-white transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </template>
</div>
