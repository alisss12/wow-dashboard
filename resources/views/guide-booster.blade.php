<x-app-layout>
    <div class="py-12 bg-gray-900 min-h-screen text-gray-100">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 rounded-2xl shadow-2xl border border-gray-700 overflow-hidden">
                <div class="h-48 bg-gradient-to-r from-indigo-900 to-purple-900 flex items-center justify-center relative">
                    <div class="absolute inset-0 opacity-30 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
                    <div class="relative text-center">
                        <h1 class="text-4xl font-extrabold text-white tracking-tight">Booster Operations Guide</h1>
                        <p class="text-indigo-200 mt-2 font-medium">Maximizing Efficiency & Quality in Every Run</p>
                    </div>
                </div>

                <div class="p-8 space-y-8">
                    <section>
                        <h2 class="text-2xl font-bold flex items-center text-indigo-400">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Core Responsibilities
                        </h2>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-700/50 p-4 rounded-xl border border-gray-600">
                                <h3 class="font-bold text-white mb-2">Punctuality</h3>
                                <p class="text-sm text-gray-300">Be at the instance entrance 15 minutes before the scheduled start time. Late starts affect our reputation.</p>
                            </div>
                            <div class="bg-gray-700/50 p-4 rounded-xl border border-gray-600">
                                <h3 class="font-bold text-white mb-2">Performance</h3>
                                <p class="text-sm text-gray-300">Maintain peak performance. We expect our boosters to be in the top 1% of the player base.</p>
                            </div>
                        </div>
                    </section>

                    <section class="bg-indigo-900/20 p-6 rounded-2xl border border-indigo-500/30">
                        <h2 class="text-xl font-bold text-indigo-300 mb-4">Run Management Workflow</h2>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <span class="bg-indigo-600 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs font-bold mr-4 mt-1 shrink-0">1</span>
                                <div>
                                    <p class="font-semibold text-white">Create the Run</p>
                                    <p class="text-sm text-gray-400">Use the "Host a Raid" button on your dashboard. Provide clear details about instance, difficulty, and loot options.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-indigo-600 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs font-bold mr-4 mt-1 shrink-0">2</span>
                                <div>
                                    <p class="font-semibold text-white">Staff Approval</p>
                                    <p class="text-sm text-gray-400">Once created, staff will review the run. Ensure your schedule doesn't conflict with other active events.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-indigo-600 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs font-bold mr-4 mt-1 shrink-0">3</span>
                                <div>
                                    <p class="font-semibold text-white">Filling Spots</p>
                                    <p class="text-sm text-gray-400">Advertisers will begin booking clients into your run. You can view the list of clients and their roles on the run detail page.</p>
                                </div>
                            </li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold text-gray-100 mb-4">Payouts & Gold</h2>
                        <p class="text-gray-400 leading-relaxed text-sm">
                            Commissions are calculated based on the total gold collected for the run. Gold must be collected <span class="text-white font-semibold">IN-GAME ONLY</span> using approved guild bank procedures. Once a run is marked as complete, your share will be deposited into your site ledger.
                        </p>
                    </section>

                    <div class="pt-6 border-t border-gray-700 flex justify-center">
                        <a href="{{ route('dashboard') }}" class="text-indigo-400 hover:text-indigo-300 font-bold transition">
                            &larr; Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
