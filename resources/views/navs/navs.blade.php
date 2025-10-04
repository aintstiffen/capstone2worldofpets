{{-- Header --}}
    <header class="bg-[var(--color-card)] border-b sticky top-0 z-10" x-data="{
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
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-[var(--color-primary)]">
                    <path d="M9.196 2.013a.75.75 0 01.692.888.893.893 0 00.16.606c.159.276.37.488.646.647.306.177.642.276 1.06.342.084.013.17.025.256.035a.75.75 0 01-.094 1.495 3.357 3.357 0 01-1.198-.93.893.893 0 00-.606-.16c-.276.16-.489.371-.647.646-.177.306-.276.642-.342 1.06-.027.173-.04.347-.05.522-.014.233-.027.468-.069.705a.75.75 0 01-1.495-.095c.048-.384.08-.773.135-1.161.028-.194.064-.387.108-.577l.009-.036a3.369 3.369 0 01.3-.9c.16-.275.37-.487.646-.646a2.404 2.404 0 011.06-.341 7.437 7.437 0 01.522-.05c.233-.013.467-.027.705-.069a.75.75 0 01.152.012zM15.164 5.13a.75.75 0 01-.84.622 2.405 2.405 0 00-1.06.341c-.276.16-.487.371-.647.647a3.392 3.392 0 00-.3.899c-.03.112-.057.225-.08.338-.052.266-.093.533-.146.799a.75.75 0 01-1.495-.096c.078-.487.138-.975.23-1.453.036-.19.08-.379.128-.565l.025-.099a2.404 2.404 0 01.341-1.06c.159-.276.371-.487.646-.647a3.369 3.369 0 01.9-.3A6.135 6.135 0 0114.925 5a6.132 6.132 0 01.705.069.75.75 0 01.534.7zM2.748 7.252a.75.75 0 01-.7.535 6.135 6.135 0 00-.528.05 6.132 6.132 0 00-.705.069.75.75 0 01-.888-.692.75.75 0 01.69-.888 3.369 3.369 0 001.198.93.893.893 0 01.606.16c.276-.159.488-.37.647-.646.177-.306.276-.642.342-1.06.013-.084.025-.17.035-.256a.75.75 0 111.494.093c-.048.385-.08.773-.134 1.162-.028.193-.063.387-.107.577l-.01.036a3.369 3.369 0 01-.3.9c-.159.275-.37.487-.646.646a2.403 2.403 0 01-1.06.341 7.432 7.432 0 01-.522.05c-.232.014-.466.027-.705.07a.75.75 0 01-.151-.012zM7.766 11.388a.75.75 0 01-.622.84c-.233.037-.466.065-.7.087a7.44 7.44 0 00-.522.05 2.403 2.403 0 00-1.06.341c-.275.159-.487.37-.646.647a3.368 3.368 0 00-.3.899c-.03.112-.058.225-.08.338-.052.266-.093.533-.147.799a.75.75 0 01-1.495-.096c.08-.486.14-.974.232-1.453.036-.19.08-.378.128-.565.018-.065.04-.13.063-.194a2.403 2.403 0 01.341-1.06c.16-.276.371-.487.647-.646a3.368 3.368 0 01.899-.301c.153-.03.307-.054.461-.075.308-.042.616-.072.929-.12a.75.75 0 01.872.605zM22.14 15.05a.75.75 0 01-.637.848c-.376.068-.757.112-1.138.152-.47.05-.943.088-1.405.176-.388.074-.774.175-1.14.312a5.89 5.89 0 00-.696.32.75.75 0 01-.848-.637.75.75 0 01.636-.848c.376-.068.757-.112 1.14-.152.47-.05.942-.087 1.404-.176.388-.074.773-.175 1.14-.312.142-.053.282-.11.42-.172.257-.117.514-.243.755-.378a.75.75 0 01.37.868zM9.11 17.346a.75.75 0 01-.694.801c-.055.004-.11.01-.165.018a5.893 5.893 0 00-.725.144c-.214.054-.424.117-.63.188-.142.049-.281.104-.418.16a8.003 8.003 0 00-.814.354.75.75 0 11-.576-1.382 9.5 9.5 0 01.967-.42c.042-.015.084-.03.127-.044.223-.075.448-.14.674-.201.202-.055.408-.102.614-.144.11-.022.22-.042.33-.06.55-.091 1.105-.152 1.66-.184a.75.75 0 01.65.77zM20.992 2.752a.75.75 0 01.799.683c.015.174.022.349.025.523.006.43-.021.858-.103 1.276a4.992 4.992 0 01-.208.8 3.572 3.572 0 01-.431.823 1.68 1.68 0 01-.436.424.593.593 0 01-.322.103.75.75 0 01-.68-.954l-.003-.02c.021-.067.045-.132.071-.195.06-.142.137-.273.228-.392.193-.254.394-.53.553-.848.112-.224.196-.457.255-.695.06-.248.095-.51.103-.776a2.724 2.724 0 00-.025-.368.75.75 0 01.174-.384z" />
                </svg>
                <span class="text-[var(--color-primary)]">Pets of World</span>
            </a>
            <nav class="hidden md:flex items-center space-x-8 text-sm">
                <a href="{{ route('dogs') }}" class="font-medium transition-colors hover:text-[var(--color-primary)] hover:underline underline-offset-4 {{ request()->routeIs('dogs*') ? 'text-[var(--color-primary)]' : '' }}">Dogs</a>
                <a href="{{ route('cats') }}" class="font-medium transition-colors hover:text-[var(--color-primary)] hover:underline underline-offset-4 {{ request()->routeIs('cats*') ? 'text-[var(--color-primary)]' : '' }}">Cats</a>
                <a href="{{ route('assessment') }}" class="font-medium transition-colors hover:text-[var(--color-primary)] hover:underline underline-offset-4 {{ request()->routeIs('assessment*') ? 'text-[var(--color-primary)]' : '' }}">Personality Assessment</a>
                <a href="/compare" class="font-medium transition-colors hover:text-[var(--color-primary)] hover:underline underline-offset-4 {{ request()->path() == 'compare' ? 'text-[var(--color-primary)]' : '' }}">Compare Breeds</a>
            </nav>
            <div class="flex items-center gap-2">
                @guest
                    <a href="{{ route('login') }}" class="hidden md:inline-flex items-center justify-center text-[var(--color-foreground)] bg-white hover:bg-[var(--color-muted)] focus:ring-4 focus:outline-none focus:ring-[var(--color-accent)] font-medium rounded-lg text-sm px-5 py-2.5 leading-none hover-lift">Login</a>
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
                        <a href="{{ route('login') }}" class="text-[var(--color-foreground)] bg-white hover:bg-[var(--color-muted)] focus:ring-4 focus:outline-none focus:ring-[var(--color-accent)] font-medium rounded-lg text-sm px-5 py-2.5 text-center">Login</a>
                        <a href="{{ route('register') }}" class="font-medium text-white bg-[var(--color-primary)] hover:bg-[var(--color-primary-dark)] rounded-md px-3 py-2 text-center">Sign Up</a>
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