{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'World of Pets - Discover Your Perfect Pet Companion')

@section('content')
    <div class="flex flex-col min-h-screen">
        {{-- Main Content --}}
        <main class="flex-1">
            {{-- Hero Section --}}
            <section class="w-full py-12 md:py-24 lg:py-32 bg-gradient-to-b from-[var(--color-card)] to-[var(--color-muted)]">
                <div class="container mx-auto px-4 md:px-6">
                    <div class="grid gap-6 lg:grid-cols-2 lg:gap-12 items-center">
                        <div class="space-y-4">
                            <h1 class="text-3xl font-bold tracking-tighter sm:text-4xl md:text-5xl">
                                Discover Your Perfect Pet Companion
                            </h1>
                            <p class="text-[var(--color-muted-foreground)] md:text-xl">
                                Explore detailed profiles of popular dog and cat breeds in the Philippines. Find your ideal
                                pet match with our personality assessment tool.
                            </p>
                            <div class="flex flex-col gap-2 min-[400px]:flex-row">
                                <a href="{{ route('assessment') }}"
                                    class="inline-flex items-center justify-center gap-1 px-5 py-2.5 bg-[var(--color-primary)] text-white rounded-md hover:bg-[var(--color-primary-dark)] transition-colors hover-lift">
                                    Take Personality Quiz
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                                <a href="/dogs" class="inline-flex items-center justify-center px-5 py-2.5 border rounded-md border-gray-300 hover:bg-[var(--color-muted)] transition-colors hover-pop">
                                    Explore Breeds
                                </a>
                            </div>
                        </div>
                        <div class="mx-auto lg:ml-auto flex justify-center w-full">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full max-w-lg">
                                <div class="h-64 sm:h-72 md:h-80 overflow-hidden rounded-lg shadow-lg hover-lift">
                                    <img src="https://imgs.search.brave.com/gTfFhk4oO_E73T8H6y_CNtbv_pzei0JoILJIucuWfCA/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9jZG4u/YnJpdGFubmljYS5j/b20vMzMvMTM2MTMz/LTAwNC0zMzg1RjZG/NS9nb2xkZW4tcmV0/cmlldmVyLmpwZw"
                                         alt="Golden Retriever" class="w-full h-full object-cover transition-transform hover:scale-105 duration-500" />
                                </div>
                                <div class="h-64 sm:h-72 md:h-80 overflow-hidden rounded-lg shadow-lg hover-lift">
                                    <img src="https://imgs.search.brave.com/6ylsIeMVYccyeHoDEgMwELzufk_vLNFuml9acMm3fTc/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9yZW5k/ZXIuZmluZWFydGFt/ZXJpY2EuY29tL2lt/YWdlcy9pbWFnZXMt/cHJvZmlsZS1mbG93/LzQwMC9pbWFnZXMt/bWVkaXVtLWxhcmdl/LTUvcGVyc2lhbi1j/YXQtc2lsdmVyc2Fs/dHBob3RvanNlbm9z/aWFpbi5qcGc"
                                         alt="Persian Cat" class="w-full h-full object-cover transition-transform hover:scale-105 duration-500" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Features Section --}}
            <section class="w-full py-12 md:py-24 lg:py-32">
                <div class="container mx-auto px-4 md:px-6">
                    <div class="flex flex-col items-center justify-center space-y-4 text-center">
                        <div class="space-y-2">
                            <h2 class="text-3xl font-bold tracking-tighter sm:text-4xl md:text-5xl">Our Features</h2>
                            <p
                                class="max-w-[900px] text-[var(--color-muted-foreground)] md:text-xl/relaxed lg:text-base/relaxed xl:text-xl/relaxed">
                                Everything you need to learn about dog and cat breeds and find your perfect pet match.
                            </p>
                        </div>
                    </div>
                    <div class="mx-auto grid max-w-5xl grid-cols-1 gap-6 py-12 md:grid-cols-2 lg:grid-cols-3">
                        {{-- Feature Card 1 --}}
                        <div class="border-0 shadow-md rounded-lg p-6 surface-card hover-lift">
                            <div class="mb-2 inline-flex h-12 w-12 items-center justify-center rounded-lg" style="background-color: color-mix(in oklab, var(--color-primary) 12%, white);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[--color-primary] animate-wiggle" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2-2 4 4M7 12a5 5 0 1110 0 5 5 0 01-10 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold">Breed Profiles</h3>
                            <p class="text-sm text-[var(--color-muted-foreground)] mt-2">
                                Detailed information on 20 dog and 10 cat breeds including size, temperament, lifespan, and
                                colors.
                            </p>
                            <div class="flex gap-2 mt-4">
                                <a href="/dogs" class="text-sm text-[var(--color-primary)] hover:underline">Dog Breeds</a>
                                <a href="/cats" class="text-sm text-[var(--color-primary)] hover:underline">Cat Breeds</a>
                            </div>
                        </div>

                        {{-- Feature Card 2 --}}
                        <div class="border-0 shadow-md rounded-lg p-6 surface-card hover-lift">
                            <div class="mb-2 inline-flex h-12 w-12 items-center justify-center rounded-lg" style="background-color: color-mix(in oklab, var(--color-primary) 12%, white);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[--color-primary] animate-wiggle" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold">Personality Assessment</h3>
                            <p class="text-sm text-[var(--color-muted-foreground)] mt-2">
                                Take our interactive quiz to discover which dog or cat breeds match your personality and
                                lifestyle.
                            </p>
                            <a href="/assessment" class="text-sm text-[var(--color-primary)] hover:underline mt-4 inline-block">Take
                                Quiz</a>
                        </div>

                        {{-- Feature Card 3 --}}
                        <div class="border-0 shadow-md rounded-lg p-6 surface-card hover-lift">
                            <div class="mb-2 inline-flex h-12 w-12 items-center justify-center rounded-lg" style="background-color: color-mix(in oklab, var(--color-primary) 12%, white);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[--color-primary] animate-wiggle" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold">Breed Comparison</h3>
                            <p class="text-sm text-[var(--color-muted-foreground)] mt-2">
                                Compare different breeds side-by-side to help you make an informed decision about your
                                future pet.
                            </p>
                            <a href="/compare" class="text-sm text-[var(--color-primary)] hover:underline mt-4 inline-block">Compare
                                Now</a>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Fun Facts Section --}}
            <section class="w-full py-12 md:py-24 lg:py-32" style="background: linear-gradient(180deg, color-mix(in oklab, var(--color-secondary) 7%, rgba(0,0,0,0)), color-mix(in oklab, var(--color-background) 80%, white));">
                                <div class="container mx-auto px-4 md:px-6">
                    <div class="grid gap-6 lg:grid-cols-3 items-center justify-center">
                        <div class="space-y-4">
                            <h2 class="text-3xl font-bold tracking-tighter sm:text-4xl">Fun Facts Feature</h2>
                            <p class="text-[var(--color-muted-foreground)] md:text-xl">
                                <span class="md:inline hidden">Hover over</span>
                                <span class="md:hidden">Tap on</span>
                                different parts of a breed image to discover interesting facts about specific
                                features.
                            </p>
                            <a href="/dogs/labrador-retriever" class="inline-block px-4 py-2 bg-[var(--color-primary)] text-white rounded-md hover:bg-[var(--color-primary-dark)] hover-lift">
                                Try It Now
                            </a>
                        </div>
                        <div class="mx-auto lg:ml-auto lg:col-span-2 flex flex-col items-center w-full max-w-md lg:max-w-3xl">
                            <div class="relative w-full">
                                <img src="https://imgs.search.brave.com/7ad6u7NSwUDebItDOPca4slPF88rKp790UGkd9rpgC0/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWFn/ZXMudW5zcGxhc2gu/Y29tL3Bob3RvLTE1/MzcyMDQ2OTY0ODYt/OTY3ZjFiNzE5OGM4/P2ZtPWpwZyZxPTYw/Jnc9MzAwMCZpeGxp/Yj1yYi00LjEuMCZp/eGlkPU0zd3hNakEz/ZkRCOE1IeHpaV0Z5/WTJoOE0zeDhiR0Zp/Y21Ga2IzSjhaVzU4/TUh4OE1IeDhmREE9"
                                    alt="Labrador Retriever with interactive hotspots"
                                    class="rounded-2xl object-cover shadow-2xl ring-1 ring-black/5 border border-[var(--color-border)] w-full h-auto max-w-2xl sm:max-w-3xl lg:max-w-none mx-auto" />

                                <!-- Ears Hotspot -->
                                <div class="absolute rounded-full border-2 flex items-center justify-center group cursor-pointer"
                                    style="top: 15%; left: 12%; height: 3rem; width: 3rem; border-color: rgba(240, 82, 82, 0.9); background-color: rgba(240, 82, 82, 0.18); animation: pulse 2s infinite;">
                                    <span class="text-xs font-bold text-pink-700">Ears</span>
                                    <!-- Tooltip -->
                                    <div
                                        class="absolute left-1/2 -translate-x-1/2 mt-10 w-56 z-10 hidden group-hover:block md:group-hover:block bg-white border border-[var(--color-primary)] rounded-lg shadow-lg p-3 text-sm text-gray-700 transition-all duration-200">
                                        <span class="font-semibold text-[var(--color-primary)]">Fun Fact:</span>
                                        Labradors have floppy ears that help protect their inner ear from debris and water.
                                        Their keen hearing makes them excellent retrievers!
                                    </div>
                                </div>

                                <!-- Fur Hotspot -->
                                <div class="absolute rounded-full border-2 flex items-center justify-center group cursor-pointer"
                                    style="top: 70%; left: 48%; height: 3rem; width: 3rem; border-color: rgba(240, 82, 82, 0.9); background-color: rgba(240, 82, 82, 0.18); animation: pulse 2s infinite;">
                                    <span class="text-xs font-bold text-pink-700">Furr</span>
                                    <div
                                        class="absolute left-1/2 -translate-x-1/2 mt-10 w-56 z-10 hidden group-hover:block md:group-hover:block bg-white border border-[var(--color-primary)] rounded-lg shadow-lg p-3 text-sm text-gray-700 transition-all duration-200">
                                        <span class="font-semibold text-[var(--color-primary)]">Fun Fact:</span>
                                        Labrador Retrievers have a double coat that repels water and keeps them warm while
                                        swimming—even in cold weather!
                                    </div>
                                </div>

                                <!-- Mouth Hotspot -->
                                <div class="absolute rounded-full border-2 flex items-center justify-center group cursor-pointer"
                                    style="top: 42%; left: 30%; height: 3rem; width: 3rem; border-color: rgba(240, 82, 82, 0.9); background-color: rgba(240, 82, 82, 0.18); animation: pulse 2s infinite;">
                                    <span class="text-xs font-bold text-pink-700">Mouth</span>
                                    <div
                                        class="absolute left-1/2 -translate-x-1/2 mt-10 w-56 z-10 hidden group-hover:block md:group-hover:block bg-white border border-[var(--color-primary)] rounded-lg shadow-lg p-3 text-sm text-gray-700 transition-all duration-200">
                                        <span class="font-semibold text-[var(--color-primary)]">Fun Fact:</span>
                                        Labradors have a "soft mouth," meaning they can carry objects gently without
                                        damaging them—a trait prized in retrieving!
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Responsive thumbnail grid to fill blanks -->
                        </div>
                    </div>
                </div>
            </section>
        </main>


    </div>
@endsection
