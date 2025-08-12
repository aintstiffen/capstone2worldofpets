{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'World of Pets - Discover Your Perfect Pet Companion')

@section('content')
<div class="flex flex-col min-h-screen">
    {{-- Main Content --}}
    <main class="flex-1">
        {{-- Hero Section --}}
        <section class="w-full py-12 md:py-24 lg:py-32 bg-gradient-to-b from-white to-muted/30">
            <div class="container px-4 md:px-6">
                <div class="grid gap-6 lg:grid-cols-2 lg:gap-12 items-center">
                    <div class="space-y-4">
                        <h1 class="text-3xl font-bold tracking-tighter sm:text-4xl md:text-5xl">
                            Discover Your Perfect Pet Companion
                        </h1>
                        <p class="text-muted-foreground md:text-xl">
                            Explore detailed profiles of popular dog and cat breeds in the Philippines. Find your ideal
                            pet match with our personality assessment tool.
                        </p>
                        <div class="flex flex-col gap-2 min-[400px]:flex-row">
                            <a href="/assessment" class="inline-flex items-center gap-1 px-4 py-2 bg-primary text-white rounded-md">
                                Take Personality Quiz
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                            <a href="{{ route('assessment') }}" 
   class="px-4 py-2 border border-black rounded-md bg-black text-white hover:bg-gray-700 hover:text-white transition">
    Take Personality Quiz
</a>
                            <a href="/compare" class="px-4 py-2 border rounded-md border-gray-300">
                                Compare Breeds
                            </a>
                        </div>
                    </div>
                    <div class="mx-auto lg:ml-auto flex justify-center">
                        <div class="grid grid-cols-2 gap-4">
                            <img src="/placeholder.svg?height=300&width=250" alt="Golden Retriever"
                                class="rounded-lg object-cover shadow-lg" width="250" height="300" />
                            <img src="/placeholder.svg?height=300&width=250" alt="Persian Cat"
                                class="rounded-lg object-cover shadow-lg" width="250" height="300" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Features Section --}}
        <section class="w-full py-12 md:py-24 lg:py-32">
            <div class="container px-4 md:px-6">
                <div class="flex flex-col items-center justify-center space-y-4 text-center">
                    <div class="space-y-2">
                        <h2 class="text-3xl font-bold tracking-tighter sm:text-4xl md:text-5xl">Our Features</h2>
                        <p class="max-w-[900px] text-muted-foreground md:text-xl/relaxed lg:text-base/relaxed xl:text-xl/relaxed">
                            Everything you need to learn about dog and cat breeds and find your perfect pet match.
                        </p>
                    </div>
                </div>
                <div class="mx-auto grid max-w-5xl grid-cols-1 gap-6 py-12 md:grid-cols-2 lg:grid-cols-3">
                    {{-- Feature Card 1 --}}
                    <div class="border-0 shadow-md rounded-lg p-6">
                        <div class="mb-2 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2-2 4 4M7 12a5 5 0 1110 0 5 5 0 01-10 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold">Breed Profiles</h3>
                        <p class="text-sm text-muted-foreground mt-2">
                            Detailed information on 20 dog and 10 cat breeds including size, temperament, lifespan, and
                            colors.
                        </p>
                        <div class="flex gap-2 mt-4">
                            <a href="/dogs" class="text-sm text-primary hover:underline">Dog Breeds</a>
                            <a href="/cats" class="text-sm text-primary hover:underline">Cat Breeds</a>
                        </div>
                    </div>

                    {{-- Feature Card 2 --}}
                    <div class="border-0 shadow-md rounded-lg p-6">
                        <div class="mb-2 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8v4l3 3" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold">Personality Assessment</h3>
                        <p class="text-sm text-muted-foreground mt-2">
                            Take our interactive quiz to discover which dog or cat breeds match your personality and
                            lifestyle.
                        </p>
                        <a href="/assessment" class="text-sm text-primary hover:underline mt-4 inline-block">Take Quiz</a>
                    </div>

                    {{-- Feature Card 3 --}}
                    <div class="border-0 shadow-md rounded-lg p-6">
                        <div class="mb-2 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold">Breed Comparison</h3>
                        <p class="text-sm text-muted-foreground mt-2">
                            Compare different breeds side-by-side to help you make an informed decision about your
                            future pet.
                        </p>
                        <a href="/compare" class="text-sm text-primary hover:underline mt-4 inline-block">Compare Now</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Fun Facts Section --}}
        <section class="w-full py-12 md:py-24 lg:py-32 bg-muted/30">
            <div class="container px-4 md:px-6">
                <div class="grid gap-6 lg:grid-cols-2 lg:gap-12 items-center">
                    <div class="space-y-4">
                        <h2 class="text-3xl font-bold tracking-tighter sm:text-4xl">Fun Facts Feature</h2>
                        <p class="text-muted-foreground md:text-xl">
                            Hover over different parts of a breed image to discover interesting facts about specific
                            features.
                        </p>
                        <a href="/dogs/labrador-retriever" class="px-4 py-2 bg-primary text-white rounded-md">
                            Try It Now
                        </a>
                    </div>
                    <div class="mx-auto lg:ml-auto flex justify-center">
                        <div class="relative">
                            <img src="/placeholder.svg?height=400&width=500"
                                alt="Labrador Retriever with interactive hotspots"
                                class="rounded-lg object-cover shadow-lg" width="500" height="400" />
                            <div class="absolute top-1/4 left-1/4 h-8 w-8 rounded-full border-2 border-primary bg-white/80 flex items-center justify-center animate-pulse">
                                <span class="text-xs font-bold text-primary">Ears</span>
                            </div>
                            <div class="absolute top-1/2 right-1/3 h-8 w-8 rounded-full border-2 border-primary bg-white/80 flex items-center justify-center animate-pulse">
                                <span class="text-xs font-bold text-primary">Tail</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer class="border-t bg-muted/40">
        <div class="container flex flex-col gap-4 py-10 md:flex-row md:gap-8">
            <div class="flex-1 space-y-4">
                <div class="flex items-center gap-2 font-bold text-xl">
                    <span class="text-primary">World of Pets</span>
                </div>
                <p class="text-sm text-muted-foreground">
                    Educational platform for dog and cat breeds in the Philippines.
                </p>
            </div>
            <div class="grid flex-1 grid-cols-2 gap-8 sm:grid-cols-3">
                <div class="space-y-3">
                    <h3 class="text-sm font-medium">Explore</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/dogs" class="text-muted-foreground transition-colors hover:text-foreground">Dog Breeds</a></li>
                        <li><a href="/cats" class="text-muted-foreground transition-colors hover:text-foreground">Cat Breeds</a></li>
                    </ul>
                </div>
                <div class="space-y-3">
                    <h3 class="text-sm font-medium">Tools</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/assessment" class="text-muted-foreground transition-colors hover:text-foreground">Personality Assessment</a></li>
                        <li><a href="/compare" class="text-muted-foreground transition-colors hover:text-foreground">Breed Comparison</a></li>
                    </ul>
                </div>
                <div class="space-y-3">
                    <h3 class="text-sm font-medium">Legal</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-muted-foreground transition-colors hover:text-foreground">Privacy Policy</a></li>
                        <li><a href="#" class="text-muted-foreground transition-colors hover:text-foreground">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="border-t py-6">
            <div class="container flex flex-col items-center justify-center gap-4 md:flex-row md:justify-between">
                <p class="text-center text-sm text-muted-foreground md:text-left">
                    © 2025 World of Pets. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</div>
@endsection
