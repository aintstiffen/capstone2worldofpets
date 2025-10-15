    <!-- resources/views/layouts/app.blade.php -->
    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <meta name="user-id" content="{{ Auth::check() ? Auth::id() : 'guest' }}">
        <title>@yield('title', config('app.name', 'Laravel'))</title>
        <!-- SEO Meta Tags -->
    <meta name="description" content="World of Pets - Discover, compare, and learn about dog and cat breeds. Find pet facts, personality assessments, and fun features for pet lovers.">
    <meta name="keywords" content="pets, dogs, cats, dog breeds, cat breeds, pet comparison, pet facts, personality assessment, fun pet gifs">
    <meta name="author" content="BSIT COLLEGE OF STI LIPA, STIFFEN DE CASTRO">

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA2NCA2NCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSJjdXJyZW50Q29sb3IiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiB3aWR0aD0iNjQiIGhlaWdodD0iNjQiPgogIDwhLS0gRG9nIEhlYWQgLS0+CiAgPHBhdGggZD0iTTE4IDQ0YzAtMTAgOC0xMiAxMC0xMnMxMCAyIDEwIDEyIiBmaWxsPSIjRjVBNjIzIi8+CiAgPGNpcmNsZSBjeD0iMjMiIGN5PSIzOSIgcj0iMyIgZmlsbD0iI2ZmZiIvPgogIDxjaXJjbGUgY3g9IjMzIiBjeT0iMzkiIHI9IjMiIGZpbGw9IiNmZmYiLz4KICA8Y2lyY2xlIGN4PSIyMyIgY3k9IjM5IiByPSIxLjUiIGZpbGw9IiMzMzMiLz4KICA8Y2lyY2xlIGN4PSIzMyIgY3k9IjM5IiByPSIxLjUiIGZpbGw9IiMzMzMiLz4KICA8cGF0aCBkPSJNIDI2IDQ2YzEgMSA0IDEgNSAweiIgc3Ryb2tlPSIjMzMzIi8+CiAgPCEtLSBEb2cgRWFyIC0tPgogIDxwYXRoIGQ9Ik0xNSA4YzItNSA2LTYgNi02IiBzdHJva2U9IiNEMTdDMTkiIHN0cm9rZS13aWR0aD0iMyIvPgogIDwhLS0gQ2F0IEhlYWQgLS0+CiAgPHBhdGggZD0iTTQ2IDQ0YzAtMTAtOC0xMi0xMC0xMnMtMTAgMi0xMCAxMiIgZmlsbD0iI0ZGQ0U1NCIvPgogIDxjaXJjbGUgY3g9IjQxIiBjeT0iMzkiIHI9IjMiIGZpbGw9IiNmZmYiLz4KICA8Y2lyY2xlIGN4PSIzMSIgY3k9IjM5IiByPSIzIiBmaWxsPSIjZmZmIi8+CiAgPGNpcmNsZSBjeD0iNDEiIGN5PSIzOSIgcj0iMS41IiBmaWxsPSIjMzMzIi8+CiAgPGNpcmNsZSBjeD0iMzEiIGN5PSIzOSIgcj0iMS41IiBmaWxsPSIjMzMzIi8+CiAgPHBhdGggZD0iTTM0IDQ2Yy0xIDEtNCAxLTUgMCIgc3Ryb2tlPSIjMzMzIi8+CiAgPCEtLSBDYXQgRWFyIC0tPgogIDxwYXRoIGQ9Ik01MCAzOGMtMi01LTYtNS02LTUiIHN0cm9rZT0iI0QxQTMxOSIgc3Ryb2tlLXdpZHRoPSIzIi8+Cjwvc3ZnPg==
" />

        <!-- Alpine.js CDN (for immediate testing) -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Pet Facts API Integration -->
        <link rel="stylesheet" href="{{ asset('css/pet-facts.css') }}">
        <link rel="stylesheet" href="{{ asset('css/mobile-responsive.css') }}">
        <script src="{{ asset('js/pet-facts.js') }}"></script>
        
        <!-- Mobile interactions -->
        <script src="{{ asset('js/mobile-interactions.js') }}"></script>
        <script src="{{ asset('js/navigation.js') }}"></script>
        
    <link rel="stylesheet" href="{{ asset('css/animations.css') }}">
    <script src="{{ asset('js/animations.js') }}" defer></script>
    @stack('styles')
    </head>

    <body class="font-sans antialiased bg-[var(--color-background)] text-[var(--color-foreground)] paw-pattern">

        {{-- Main Content --}}
        @include('navs.navs')
        <main class="animated-fade-in">
            @yield('content')
        </main>
        @include('footer.footer')
        
        @stack('scripts')
    </body>

    </html>
