    <!-- resources/views/layouts/app.blade.php -->
    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="user-id" content="{{ Auth::check() ? Auth::id() : 'guest' }}">
        <title>@yield('title', config('app.name', 'Laravel'))</title>

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
        
        @stack('styles')
    </head>

    <body class="font-sans antialiased bg-gray-50 text-gray-900">

        {{-- Main Content --}}
        @include('navs.navs')
        @yield('content')
        @include('footer.footer')
        
        @stack('scripts')
    </body>

    </html>
