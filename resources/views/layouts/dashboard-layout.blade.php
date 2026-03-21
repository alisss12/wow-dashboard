<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DawnHub') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full bg-gray-100 text-gray-900 antialiased" x-data="{ mobileMenuOpen: false }" @keydown.window.escape="mobileMenuOpen = false">

    @include('layouts.partials.toast')

    @include('layouts.partials.sidebar')

    <div class="lg:pl-64 flex flex-col min-h-screen">
        @include('layouts.partials.navbar')

        <main class="py-10">
            <div class="px-4 sm:px-6 lg:px-8">
                    @if(isset($component))
                        @livewire($component, ['sharedData' => $sharedData ?? []])
                    @else
                        {{ $slot }}
                    @endif
                </div>
            </main>

            <footer class="mt-auto border-t border-slate-800 py-6">
                <div class="px-4 sm:px-6 lg:px-8 text-center">
                    <p class="text-xs text-slate-500 uppercase tracking-widest">&copy; {{ date('Y') }} DAWHHUB | ALL RIGHTS RESERVED</p>
                </div>
            </footer>
        </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('copy-to-clipboard', (event) => {
                const content = Array.isArray(event) ? event[0].content : event.content;
                navigator.clipboard.writeText(content).then(() => {
                    window.dispatchEvent(new CustomEvent('notify', { 
                        detail: { type: 'success', message: 'Copied to clipboard!' } 
                    }));
                });
            });
        });
    </script>
</body>
</html>
