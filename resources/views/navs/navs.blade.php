{{-- Header --}}
    <header class="bg-white border-b sticky top-0 z-10">
        <div class="container flex items-center justify-between h-16 px-4 md:px-6">
            <a href="/" class="flex items-center gap-2 font-bold text-xl">
                <span class="text-primary">World of Pets</span>
            </a>
            <nav class="hidden md:flex items-center gap-6 text-sm">
                <a href="{{ route('dogs') }}" class="font-medium transition-colors hover:text-primary">Dogs</a>
                <a href="{{ route('cats') }}" class="font-medium transition-colors hover:text-primary">Cats</a>
                <a href="/assessment" class="font-medium transition-colors hover:text-primary">Personality Assessment</a>
                <a href="/compare" class="font-medium transition-colors hover:text-primary">Compare Breeds</a>
            </nav>
            <div class="flex items-center gap-2">
                <button class="rounded-full border border-gray-300 px-3 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span class="sr-only">Search</span>
                </button>
                <button class="rounded-full border border-gray-300 px-3 py-2 md:hidden">
                    <span class="sr-only">Menu</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <line x1="4" x2="20" y1="12" y2="12" />
                        <line x1="4" x2="20" y1="6" y2="6" />
                        <line x1="4" x2="20" y1="18" y2="18" />
                    </svg>
                </button>
            </div>
        </div>
    </header>
