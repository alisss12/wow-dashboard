<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <script>
        // Global Tab Persistence Logic
        document.addEventListener('DOMContentLoaded', function () {
            // Function to sync current tab to any form submission
            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (form.tagName !== 'FORM') return;

                // Sync from Alpine if exists, otherwise fallback to URL
                let currentTab = null;
                const alpineRoot = document.querySelector('[x-data]');
                if (alpineRoot && Alpine) {
                    const alpineData = Alpine.$data(alpineRoot);
                    currentTab = alpineData.activeSection || alpineData.tab;
                }

                if (!currentTab) {
                    currentTab = new URLSearchParams(window.location.search).get('tab');
                }

                if (currentTab) {
                    const action = form.getAttribute('action') || window.location.pathname;
                    try {
                        const url = new URL(action, window.location.origin);
                        url.searchParams.set('tab', currentTab);
                        form.setAttribute('action', url.pathname + url.search);
                    } catch (err) {
                        console.error('Failed to append tab to form action:', err);
                    }
                }
            });

            // Handle back button specifically for dashboard links
            window.addEventListener('popstate', function() {
                const alpineRoot = document.querySelector('[x-data]');
                if (alpineRoot && Alpine) {
                    const alpineData = Alpine.$data(alpineRoot);
                    const tab = new URLSearchParams(window.location.search).get('tab');
                    if (tab) {
                        if (alpineData.activeSection !== undefined) alpineData.activeSection = tab;
                        if (alpineData.tab !== undefined) alpineData.tab = tab;
                    }
                }
            });
        });
        </script>
    </body>
</html>
