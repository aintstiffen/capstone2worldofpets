{{-- Header --}}
    <header class="bg-white border-b sticky top-0 z-10">
        <div class="container flex items-center justify-between h-16 px-4 md:px-6">
            <a href="/" class="flex items-center gap-2 font-bold text-xl">
                <span class="text-primary">World of Pets</span>
            </a>
            <nav class="hidden md:flex items-center gap-6 text-sm">
                <a href="{{ route('dogs') }}" class="font-medium transition-colors hover:text-primary">Dogs</a>
                <a href="{{ route('cats') }}" class="font-medium transition-colors hover:text-primary">Cats</a>
                <a href="{{ route('assessment') }}" class="font-medium transition-colors hover:text-primary">Personality Assessment</a>
                <a href="/compare" class="font-medium transition-colors hover:text-primary">Compare Breeds</a>
            </nav>
            <div class="flex items-center gap-2">
                @guest
                    <a href="{{ route('login') }}" class="text-white bg-[#050708] hover:bg-[#050708]/90 focus:ring-4 focus:outline-none focus:ring-[#050708]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#050708]/50 dark:hover:bg-[#050708]/30 me-2 mb-2">Login</a>
                    <a href="{{ route('register') }}" class="font-medium text-white bg-primary hover:bg-primary-dark rounded-md px-3 py-2">Sign Up</a>
                @else
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 hover:text-primary focus:outline-none">
                            <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-gray-200">
                                <img src="{{ Auth::user()->getProfilePictureUrl() }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false" 
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95">
                            
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Profile Settings
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
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
