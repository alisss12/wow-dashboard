<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Advertiser Guide') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10">
                <div class="prose max-w-none">
                    <h1 class="text-3xl font-bold mb-6">Welcome to the GOW Boosting Team</h1>
                    <p class="mb-4">As an advertiser, you are the face of our service. Your primary role is to connect players with our elite boosters while maintaining the highest standards of professionalism.</p>

                    <h2 class="text-2xl font-semibold mt-8 mb-4">1. Finding Clients</h2>
                    <p>Use the WoW Trade Chat and LFG tools responsibly. Avoid spamming and always use our official macros. Focus on high-demand periods like reset days.</p>

                    <h2 class="text-2xl font-semibold mt-8 mb-4">2. Collecting Gold</h2>
                    <ul class="list-disc pl-6 mb-4">
                        <li>Ensure you collect the full amount before the boost begins.</li>
                        <li>Verify the server and faction of the gold.</li>
                        <li>Take a screenshot of every trade for verification.</li>
                    </ul>

                    <h2 class="text-2xl font-semibold mt-8 mb-4">3. Creating Bookings</h2>
                    <p>Use the Discord <code>/mtplus</code> or <code>!raid</code> commands to create a dedicated channel for your client. This is where you will coordinate with the boosters assigned to your run.</p>

                    <h2 class="text-2xl font-semibold mt-8 mb-4">4. Commission & Payouts</h2>
                    <p>Your commission (typically 35%) is automatically calculated and added to your ledger balance upon successful completion of a boost. Payouts are processed every Monday via the Ledger system.</p>

                    <div class="mt-10 p-6 bg-yellow-50 border-l-4 border-yellow-400">
                        <h3 class="text-lg font-bold text-yellow-800">Important: Terms of Service</h3>
                        <p class="text-yellow-700">Never mention Real Money Transactions (RMT). We are a gold-only service. Failure to comply results in immediate termination.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
