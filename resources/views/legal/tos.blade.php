<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Terms of Service') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10">
                <div class="prose max-w-none text-gray-600">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Terms of Service</h1>
                    <p class="mb-4 text-sm">Last Updated: {{ date('F j, Y') }}</p>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-4">1. Nature of Services</h2>
                    <p>GOW Boosting provides in-game assistance for World of Warcraft players. All transactions within our platform are strictly conducted using in-game currency (Gold). We do not facilitate, support, or condone Real Money Transactions (RMT).</p>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-4">2. Compliance with Blizzard ToS</h2>
                    <p>While we aim to operate within the guidelines provided by Blizzard Entertainment, users acknowledge that boosting services exist in a "gray area" of the Terms of Service. GOW Boosting is not responsible for any actions taken by Blizzard against user accounts.</p>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-4">3. Refund Policy</h2>
                    <p>In the event that a boost is not completed successfully, a full refund of the in-game gold will be issued to the client. Refunds are processed within 24 hours of a reported failure.</p>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-4">4. Conduct</h2>
                    <p>Users, advertisers, and boosters are expected to maintain professional conduct. Harassment, toxicity, or any form of cheating (hacking/botting) is strictly prohibited and will result in a permanent ban from our services.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
