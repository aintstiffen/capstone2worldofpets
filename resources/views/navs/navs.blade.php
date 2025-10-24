{{-- Header --}}
    <style>
        /* Keep hotspot info panels under the sticky header */
        .info-panel { position: relative; z-index: 10; }
        .tooltip-image-container { position: relative; z-index: 0; }
    </style>
    <header class="bg-[var(--color-card)] border-b sticky top-0 z-50" x-data="{
        scrolled: false,
        lastScrollY: 0,
        mobileMenuOpen: false,
        checkScroll() {
            this.scrolled = window.scrollY > 20;
            // Hide mobile menu when scrolling down, show when scrolling up
            if (window.innerWidth < 768) {
                if (window.scrollY > this.lastScrollY && window.scrollY > 100) {
                    this.mobileMenuOpen = false; // Hide when scrolling down
                }
                this.lastScrollY = window.scrollY;
            }
        }
    }" x-init="
        checkScroll();
        window.addEventListener('scroll', checkScroll);
    " :class="{'shadow-md': scrolled}">
        <div class="container mx-auto flex items-center justify-between h-16 px-4 md:px-6">
            <a href="/" class="flex items-center gap-2 font-bold text-xl">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 200" class="w-16 h-16">
  <!-- Dog -->
  <g>
    <!-- Head -->
    <circle cx="80" cy="70" r="35" fill="#F4D19B" />
    <!-- Ears -->
    <ellipse cx="45" cy="60" rx="10" ry="20" fill="#C69763" />
    <ellipse cx="115" cy="60" rx="10" ry="20" fill="#C69763" />
    <!-- Eyes -->
    <circle cx="68" cy="68" r="5" fill="#333" />
    <circle cx="92" cy="68" r="5" fill="#333" />
    <!-- Nose -->
    <circle cx="80" cy="80" r="3" fill="#000" />
    <!-- Mouth -->
    <path d="M70 90 Q80 100 90 90" stroke="#333" stroke-width="2" fill="none" />
    <!-- Body -->
    <ellipse cx="80" cy="140" rx="35" ry="40" fill="#F4D19B" />
    <!-- Paws -->
    <circle cx="60" cy="170" r="6" fill="#C69763" />
    <circle cx="100" cy="170" r="6" fill="#C69763" />
  </g>

  <!-- Cat -->
  <g>
    <!-- Head -->
    <circle cx="230" cy="70" r="30" fill="#F7B6C2" />
    <!-- Ears -->
    <polygon points="205,55 210,35 220,60" fill="#F7B6C2" />
    <polygon points="255,55 250,35 240,60" fill="#F7B6C2" />
    <!-- Eyes -->
    <circle cx="220" cy="65" r="4" fill="#333" />
    <circle cx="240" cy="65" r="4" fill="#333" />
    <!-- Nose -->
    <circle cx="230" cy="75" r="2.5" fill="#000" />
    <!-- Mouth -->
    <path d="M224 83 Q230 90 236 83" stroke="#333" stroke-width="2" fill="none" />
    <!-- Whiskers -->
    <path d="M200 75 H215 M245 75 H260" stroke="#333" stroke-width="1" />
    <!-- Body -->
    <ellipse cx="230" cy="140" rx="30" ry="35" fill="#F7B6C2" />
    <!-- Paws -->
    <circle cx="212" cy="165" r="5" fill="#F49FB0" />
    <circle cx="248" cy="165" r="5" fill="#F49FB0" />
    <!-- Tail -->
    <path d="M260 140 Q280 130 270 160" stroke="#F7B6C2" stroke-width="6" fill="none" />
  </g>
</svg>


                <span class="text-[var(--color-primary)]">World of Pets</span>
            </a>
            <nav class="hidden md:flex items-center space-x-8 text-sm">
                <a href="{{ route('dogs') }}" class="font-medium transition-colors hover:text-[var(--color-primary)] hover:underline underline-offset-4 {{ request()->routeIs('dogs*') ? 'text-[var(--color-primary)]' : '' }}">Dogs</a>
                <a href="{{ route('cats') }}" class="font-medium transition-colors hover:text-[var(--color-primary)] hover:underline underline-offset-4 {{ request()->routeIs('cats*') ? 'text-[var(--color-primary)]' : '' }}">Cats</a>
                <a href="{{ route('assessment') }}" class="font-medium transition-colors hover:text-[var(--color-primary)] hover:underline underline-offset-4 {{ request()->routeIs('assessment*') ? 'text-[var(--color-primary)]' : '' }}">Breed Recommendations</a>
                <a href="/compare" class="font-medium transition-colors hover:text-[var(--color-primary)] hover:underline underline-offset-4 {{ request()->path() == 'compare' ? 'text-[var(--color-primary)]' : '' }}">Compare Breeds</a>
            </nav>
            <div class="flex items-center gap-2">
                @guest
                    <a href="{{ route('login') }}" class="hidden md:inline-flex items-center justify-center text-[var(--color-foreground)] bg-[color-mix(in_oklab,var(--color-primary)_8%,white)] hover:bg-[color-mix(in_oklab,var(--color-primary)_15%,white)] focus:ring-4 focus:outline-none focus:ring-[var(--color-accent)] font-medium rounded-lg text-sm px-5 py-2.5 leading-none hover-lift">Login</a>
                    <a href="{{ route('register') }}" class="hidden md:inline-flex items-center justify-center font-medium text-white bg-[var(--color-primary)] hover:bg-[var(--color-primary-dark)] rounded-lg text-sm px-5 py-2.5 leading-none hover-lift">Sign Up</a>
                @else
                    <div class="relative hidden md:block" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 hover:text-[var(--color-primary)] focus:outline-none">
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
                            
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-[var(--color-muted)]">
                               My Profile
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-[var(--color-muted)]">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
                
                <!-- Mobile Menu Toggle Button -->
                <button 
                    @click="mobileMenuOpen = !mobileMenuOpen" 
                    class="inline-flex md:hidden items-center justify-center p-2 ml-2 rounded-md text-gray-700 hover:text-[var(--color-primary)] hover:bg-[var(--color-muted)] focus:outline-none"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile navigation menu -->
        <div 
            x-show="mobileMenuOpen" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform -translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-4"
            class="fixed top-16 left-0 right-0 z-40 md:hidden bg-[var(--color-card)] border-t border-gray-200 shadow-lg max-h-[60vh] overflow-y-auto"
            style="max-height: calc(100vh - 4rem);"
            @click.away="mobileMenuOpen = false">
            
            <nav class="flex flex-col py-4 px-4 space-y-4">
                <a href="{{ route('dogs') }}" class="font-medium text-gray-900 hover:text-[var(--color-primary)] py-2 px-2 {{ request()->routeIs('dogs*') ? 'text-[var(--color-primary)]' : '' }}">Dogs</a>
                <a href="{{ route('cats') }}" class="font-medium text-gray-900 hover:text-[var(--color-primary)] py-2 px-2 {{ request()->routeIs('cats*') ? 'text-[var(--color-primary)]' : '' }}">Cats</a>
                <a href="{{ route('assessment') }}" class="font-medium text-gray-900 hover:text-[var(--color-primary)] py-2 px-2 {{ request()->routeIs('assessment*') ? 'text-[var(--color-primary)]' : '' }}">Personality Assessment</a>
                <a href="/compare" class="font-medium text-gray-900 hover:text-[var(--color-primary)] py-2 px-2 {{ request()->path() == 'compare' ? 'text-[var(--color-primary)]' : '' }}">Compare Breeds</a>
                
                @guest
                    <div class="border-t border-gray-200 pt-4 mt-2 flex flex-col space-y-3">
                        <a href="{{ route('login') }}" class="text-[var(--color-foreground)] bg-[color-mix(in_oklab,var(--color-primary)_8%,white)] hover:bg-[color-mix(in_oklab,var(--color-primary)_15%,white)] focus:ring-4 focus:outline-none focus:ring-[var(--color-accent)] font-medium rounded-lg text-sm px-5 py-2.5 text-center hover-lift">Login</a>
                        <a href="{{ route('register') }}" class="font-medium text-white bg-[var(--color-primary)] hover:bg-[var(--color-primary-dark)] rounded-lg text-sm px-5 py-2.5 text-center hover-lift">Sign Up</a>
                    </div>
                @else
                    <div class="border-t border-gray-200 pt-4 mt-2">
                        <div class="flex items-center gap-3 mb-4 px-2">
                            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-gray-200">
                                <img src="{{ Auth::user()->getProfilePictureUrl() }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        
                        <a href="{{ route('profile.edit') }}" class="block font-medium text-gray-900 hover:text-[var(--color-primary)] py-2 px-2">
                           My Profile
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left font-medium text-gray-900 hover:text-[var(--color-primary)] py-2 px-2">
                                Logout
                            </button>
                        </form>
                    </div>
                @endguest
            </nav>
        </div>
    </header>