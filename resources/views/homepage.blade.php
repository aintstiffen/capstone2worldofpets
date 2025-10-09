{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'World of Pets - Discover Your Perfect Pet Companion')

@section('content')
    <div class="flex flex-col min-h-screen">
                                <!-- Ears Hotspot (plain, centered label) -->
                                <div class="absolute hotspot-wrapper" style="top:15%; left:12%;">
                                    <div class="hotspot-circle" aria-hidden="true"></div>
                                    <span class="hotspot-label">Ears</span>
                                </div>

                                <!-- Fur Hotspot (plain, centered label) -->
                                <div class="absolute hotspot-wrapper" style="top:70%; left:48%;">
                                    <div class="hotspot-circle" aria-hidden="true"></div>
                                    <span class="hotspot-label">Fur</span>
                                </div>

                                <!-- Mouth Hotspot (plain, centered label) -->
                                <div class="absolute hotspot-wrapper" style="top:42%; left:30%;">
                                    <div class="hotspot-circle" aria-hidden="true"></div>
                                    <span class="hotspot-label">Mouth</span>
                                </div>
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

                                <!-- Ears Hotspot (circle + centered label) -->
                                <div class="absolute hotspot-wrapper" style="top: 15%; left: 12%;">
                                    <div class="hotspot-circle" aria-hidden="true"></div>
                                    <span class="hotspot-label">Ears</span>
                                </div>

                                <!-- Fur Hotspot (circle + centered label) -->
                                <div class="absolute hotspot-wrapper" style="top: 70%; left: 48%;">
                                    <div class="hotspot-circle" aria-hidden="true"></div>
                                    <span class="hotspot-label">Fur</span>
                                </div>

                                <!-- Mouth Hotspot (circle + centered label) -->
                                <div class="absolute hotspot-wrapper" style="top: 42%; left: 30%;">
                                    <div class="hotspot-circle" aria-hidden="true"></div>
                                    <span class="hotspot-label">Mouth</span>
                                </div>
                            </div>

                            <style>
                                .hotspot-wrapper {
                                    position: absolute;
                                    transform: translate(-50%, -50%);
                                    display: grid;
                                    place-items: center;
                                    pointer-events: auto;
                                }
                                .hotspot-circle {
                                    width: clamp(44px, 4.8vw, 84px);
                                    height: clamp(44px, 4.8vw, 84px);
                                    border-radius: 9999px;
                                    border: 2px solid rgba(240,82,82,0.9);
                                    background-color: rgba(240,82,82,0.12);
                                    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
                                    display: block;
                                    animation: pulse 2s infinite;
                                }
                                .hotspot-label {
                                    position: absolute;
                                    color: #d81f60;
                                    font-weight: 700;
                                    font-size: clamp(12px, 1.6vw, 14px);
                                    pointer-events: none;
                                    text-align: center;
                                }
                                @media (max-width: 640px) {
                                    .hotspot-circle { width: clamp(36px, 7.5vw, 56px); height: clamp(36px, 7.5vw, 56px); }
                                    .hotspot-label { font-size: 12px; }
                                }
                            </style>
                            </div>
                            
                            <!-- Responsive thumbnail grid to fill blanks -->
                        </div>
                    </div>
                </div>
            </section>
        </main>


    </div>
@endsection
