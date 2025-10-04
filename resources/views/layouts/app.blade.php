    <!-- resources/views/layouts/app.blade.php -->
    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <meta name="user-id" content="{{ Auth::check() ? Auth::id() : 'guest' }}">
        <title>@yield('title', config('app.name', 'Laravel'))</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0iI2ZmNmI2YiI+PHBhdGggZD0iTTkuMTk2IDIuMDEzYS43NS43NSAwIDAxLjY5Mi44ODguODkzLjg5MyAwIDAwLjE2LjYwNmMuMTU5LjI3Ni4zNy40ODguNjQ2LjY0Ny4zMDYuMTc3LjY0Mi4yNzYgMS4wNi4zNDIuMDg0LjAxMy4xNy4wMjUuMjU2LjAzNWEuNzUuNzUgMCMxLS4wOTQgMS40OTUgMy4zNTcgMy4zNTcgMCMxLTEuMTk4LS45My44OTMuODkzIDAgMDAtLjYwNi0uMTZjLS4yNzYuMTYtLjQ4OS4zNzEtLjY0Ny42NDYtLjE3Ny4zMDYtLjI3Ni42NDItLjM0MiAxLjA2LS4wMjcuMTczLS4wNC4zNDctLjA1LjUyMi0uMDE0LjIzMy0uMDI3LjQ2OC0uMDY5LjcwNWEuNzUuNzUgMCMxLTEuNDk1LS4wOTVjLjA0OC0uMzg0LjA4LS43NzMuMTM1LTEuMTYxLjAyOC0uMTk0LjA2NC0uMzg3LjEwOC0uNTc3bC4wMDktLjAzNmEzLjM2OSAzLjM2OSAwIDAxLjMtLjljLjE2LS4yNzUuMzctLjQ4Ny42NDYtLjY0NmEyLjQwNCAyLjQwNCAwIDAxMS4wNi0uMzQxIDcuNDM3IDcuNDM3IDAgMDEuNTIyLS4wNWMuMjMzLS4wMTMuNDY3LS4wMjcuNzA1LS4wNjlhLjc1Ljc1IDAgMDEuMTUyLjAxMnpNMTUuMTY0IDUuMTNhLjc1Ljc1IDAgMDEtLjg0LjYyMiAyLjQwNSAyLjQwNSAwIDAwLTEuMDYuMzQxYy0uMjc2LjE2LS40ODcuMzcxLS42NDcuNjQ3YTMuMzkyIDMuMzkyIDAgMDAtLjMuODk5Yy0uMDMuMTEyLS4wNTcuMjI1LS4wOC4zMzgtLjA1Mi4yNjYtLjA5My41MzMtLjE0Ni43OTlhLjc1Ljc1IDAgMDEtMS40OTUtLjA5NmMuMDc4LS40ODcuMTM4LS45NzUuMjMtMS40NTMuMDM2LS4xOS4wOC0uMzc5LjEyOC0uNTY1bC4wMjUtLjA5OWEyLjQwNCAyLjQwNCAwIDAxLjM0MS0xLjA2Yy4xNTktLjI3Ni4zNzEtLjQ4Ny42NDYtLjY0N2EzLjM2OSAzLjM2OSAwIDAxLjktLjNBNi4xMzUgNi4xMzUgMCMxMTQuOTI1IDVhNi4xMzIgNi4xMzIgMCMxLjcwNS4wNjlhLjc1Ljc1IDAgMDEuNTM0Ljd6TTIuNzQ4IDcuMjUyYS43NS43NSAwIDAxLS43LjUzNSA2LjEzNSA2LjEzNSAwIDAwLS41MjguMDUgNi4xMzIgNi4xMzIgMCMwLS43MDUuMDY5Ljc1Ljc1IDAgMDEtLjg4OC0uNjkyLjc1Ljc1IDAgMDEuNjktLjg4OCAzLjM2OSAzLjM2OSAwIDAxMS4xOTguOTMuODkzLjg5MyAwIDAxLjYwNi4xNmMuMjc2LS4xNTkuNDg4LS4zNy42NDctLjY0Ni4xNzctLjMwNi4yNzYtLjY0Mi4zNDItMS4wNi4wMTMtLjA4NC4wMjUtLjE3LjAzNS0uMjU2YS43NS43NSAwIDExMS40OTQuMDkzYy0uMDQ4LjM4NS0uMDguNzczLS4xMzQgMS4xNjItLjAyOC4xOTMtLjA2My4zODctLjEwNy41NzdsLS4wMS4wMzZhMy4zNjkgMy4zNjkgMCMxLS4zLjljLS4xNTkuMjc1LS4zNy40ODctLjY0Ni42NDZhMi40MDMgMi40MDMgMCMxLTEuMDYuMzQxIDcuNDMyIDcuNDMyIDAgMDEtLjUyMi4wNWMtLjIzMi4wMTQtLjQ2Ni4wMjctLjcwNS4wN2EuNzUuNzUgMCMxLS4xNTEtLjAxMnpNNy43NjYgMTEuMzg4YS43NS43NSAwIDAxLS42MjIuODRjLS4yMzMuMDM3LS40NjYuMDY1LS43LjA4N2E3LjQ0IDcuNDQgMCMwLS41MjIuMDUgMi40MDMgMi40MDMgMCMwLTEuMDYuMzQxYy0uMjc1LjE1OS0uNDg3LjM3LS42NDYuNjQ3YTMuMzY4IDMuMzY4IDAgMDAtLjMuODk5Yy0uMDMuMTEyLS4wNTguMjI1LS4wOC4zMzgtLjA1Mi4yNjYtLjA5My41MzMtLjE0Ny43OTlhLjc1Ljc1IDAgMDEtMS40OTUtLjA5NmMuMDgtLjQ4Ni4xNC0uOTc0LjIzMi0xLjQ1My4wMzYtLjE5LjA4LS4zNzguMTI4LS41NjUuMDE4LS4wNjUuMDQtLjEzLjA2My0uMTk0YTIuNDAzIDIuNDAzIDAgMDEuMzQxLTEuMDZjLjE2LS4yNzYuMzcxLS40ODcuNjQ3LS42NDZhMy4zNjggMy4zNjggMCMxLjg5OS0uMzAxYy4xNTMtLjAzLjMwNy0uMDU0LjQ2MS0uMDc1LjMwOC0uMDQyLjYxNi0uMDcyLjkyOS0uMTJhLjc1Ljc1IDAgMDEuODcyLjYwNXpNMjIuMTQgMTUuMDVhLjc1Ljc1IDAgMDEtLjYzNy44NDhjLS4zNzYuMDY4LS43NTcuMTEyLTEuMTM4LjE1Mi0uNDcuMDUtLjk0My4wODgtMS40MDUuMTc2LS4zODguMDc0LS43NzQuMTc1LTEuMTQuMzEyYTUuODkgNS44OSAwIDAwLS42OTYuMzJhLjc1Ljc1IDAgMDEtLjg0OC0uNjM3Ljc1Ljc1IDAgMDEuNjM2LS44NDhjLjM3Ni0uMDY4Ljc1Ny0uMTEyIDEuMTQtLjE1Mi40Ny0uMDUuOTQyLS4wODcgMS40MDQtLjE3Ni4zODgtLjA3NC43NzMtLjE3NSAxLjE0LS4zMTIuMTQyLS4wNTMuMjgyLS4xMS40Mi0uMTcyLjI1Ny0uMTE3LjUxNC0uMjQzLjc1NS0uMzc4YS43NS43NSAwIDAxLjM3Ljg2OHpNOS4xMSAxNy4zNDZhLjc1Ljc1IDAgMDEtLjY5NC44MDFjLS4wNTUuMDA0LS4xMS4wMS0uMTY1LjAxOGE1Ljg5MyA1Ljg5MyAwIDAwLS43MjUuMTQ0Yy0uMjE0LjA1NC0uNDI0LjExNy0uNjMuMTg4LS4xNDIuMDQ5LS4yODEuMTA0LS40MTguMTZhOC4wMDMgOC4wMDMgMCMwLS44MTQuMzU0Ljc1Ljc1IDAgMTEtLjU3Ni0xLjM4MiA5LjUgOS41IDAgMDEuOTY3LS40MmMuMDQyLS4wMTUuMDg0LS4wMy4xMjctLjA0NC4yMjMtLjA3NS40NDgtLjE0LjY3NC0uMjAxLjIwMi0uMDU1LjQwOC0uMTAyLjYxNC0uMTQ0LjExLS4wMjIuMjItLjA0Mi4zMy0uMDYuNTUtLjA5MSAxLjEwNS0uMTUyIDEuNjYtLjE4NGEuNzUuNzUgMCMxLjY1Ljc3ek0yMC45OTIgMi43NTJhLjc1Ljc1IDAgMDEuNzk5LjY4M2MuMDE1LjE3NC4wMjIuMzQ5LjAyNS41MjMuMDA2LjQzLS4wMjEuODU4LS4xMDMgMS4yNzZhNC45OTIgNC45OTIgMCMxLS4yMDguOCAzLjU3MiAzLjU3MiAwIDAxLS40MzEuODIzIDEuNjggMS42OCAwIDAxLS40MzYuNDI0LjU5My41OTMgMCMxLS4zMjIuMTAzLjc1Ljc1IDAgMDEtLjY4LS45NTRsLS4wMDMtLjAyYy4wMjEtLjA2Ny4wNDUtLjEzMi4wNzEtLjE5NS4wNi0uMTQyLjEzNy0uMjczLjIyOC0uMzkyLjE5My0uMjU0LjM5NC0uNTMuNTUzLS44NDguMTEyLS4yMjQuMTk2LS40NTcuMjU1LS42OTUuMDYtLjI0OC4wOTUtLjUxLjEwMy0uNzc2YTIuNzI0IDIuNzI0IDAgMDAtLjAyNS0uMzY4Ljc1Ljc1IDAgMDEuMTc0LS4zODR6Ii8+PC9zdmc+" />

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

    <body class="font-sans antialiased bg-[var(--color-background)] text-[var(--color-foreground)] paw-pattern">

        {{-- Main Content --}}
        @include('navs.navs')
        @yield('content')
        @include('footer.footer')
        
        @stack('scripts')
    </body>

    </html>
