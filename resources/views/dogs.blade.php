@extends('layouts.app')

@section('content')
    <div class="flex flex-col min-h-screen">


        <!-- Hero Section -->
        <main class="flex-1">
    <section class="w-full py-12 md:py-24 lg:py-32 paw-pattern" style="background: linear-gradient(180deg, color-mix(in oklab, var(--color-secondary) 8%, white), var(--color-muted));">
            <div class="container mx-auto px-4 md:px-6 text-center">
                <h1 class="text-3xl font-bold tracking-tighter sm:text-4xl md:text-5xl">Dog Breeds</h1>
                <p
                    class="max-w-[900px] mx-auto text-[--color-muted-foreground] md:text-xl/relaxed lg:text-base/relaxed xl:text-xl/relaxed">
                    Explore detailed profiles of the most popular dog breeds in the Philippines.
                </p>
            </div>
        </section>            <!-- Dog Breed Cards -->
            <section class="w-full py-12 md:py-24 lg:py-32">
            <div class="container mx-auto px-4 md:px-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">                        @if (isset($pets) && $pets->count() > 0)
                            @foreach ($pets as $pet)
                                <a href="{{ route('dogs.show', $pet->slug) }}" class="group hover-lift">
                                    <div class="overflow-hidden border rounded-lg transition-all hover:shadow-lg surface-card hover:border-[--color-primary]">
                                        <div class="aspect-square relative">
                                            <img src="{{ $pet->image ? $pet->image_url : '/placeholder.svg?height=300&width=300' }}"
                                                alt="{{ $pet->name }}"
                                                class="object-cover w-full h-full transition-transform group-hover:scale-105">
                                        </div>
                                        <div class="p-4">
                                            <h2 class="font-bold text-lg truncate">{{ $pet->name }}</h2>
                                            <p class="text-sm text-[var(--color-muted-foreground)] truncate">
                                                {{ $pet->temperament ?? 'N/A' }}</p>
                                        </div>
                                        <hr>
                                        <div class="p-4 flex justify-between items-center text-sm">
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-0.5 rounded-full" style="background-color: color-mix(in oklab, var(--color-primary) 12%, white); color: var(--color-primary);">{{ $pet->size ?? 'N/A' }}</span>
                                                <span class="text-[var(--color-muted-foreground)]">•</span>
                                                <span class="px-2 py-0.5 rounded-full text-[--color-primary]" style="background-color: color-mix(in oklab, var(--color-primary) 10%, white);">{{ $pet->lifespan ?? 'N/A' }} years</span>
                                            </div>
                                            <span class="rounded-full border p-1 text-[--color-primary]">➜</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <!-- Show message when no dog breeds are found -->
                            <div class="col-span-full text-center py-12">
                                <h3 class="text-xl font-semibold mb-2">No Dog Breeds Found</h3>
                                <p class="text-muted-foreground">Please add some dog breeds to the database.</p>
                            </div>
                        @endif





                    </div>
                    
                    <!-- Pagination Links -->
                    @if($pets->hasPages())
                        <div class="mt-12 flex justify-center">
                            <nav class="flex items-center gap-2">
                                {{-- Previous Page Link --}}
                                @if ($pets->onFirstPage())
                                    <span class="px-4 py-2 text-sm font-medium text-[--color-muted-foreground] bg-[--color-muted] border border-gray-300 rounded-lg cursor-not-allowed">
                                        ← Previous
                                    </span>
                                @else
                                    <a href="{{ $pets->previousPageUrl() }}" class="px-4 py-2 text-sm font-medium text-[--color-foreground] bg-white border border-gray-300 rounded-lg hover:bg-[--color-muted] hover:border-[--color-border] transition-colors">
                                        ← Previous
                                    </a>
                                @endif

                                {{-- Page Numbers --}}
                                <div class="flex items-center gap-1">
                                    @foreach ($pets->getUrlRange(1, $pets->lastPage()) as $page => $url)
                                        @if ($page == $pets->currentPage())
                                            <span class="px-4 py-2 text-sm font-bold text-white bg-[--color-primary] border border-[--color-primary] rounded-lg">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <a href="{{ $url }}" class="px-4 py-2 text-sm font-medium text-[--color-foreground] bg-white border border-gray-300 rounded-lg hover:bg-[--color-muted] hover:border-[--color-border] transition-colors">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>

                                {{-- Next Page Link --}}
                                @if ($pets->hasMorePages())
                                    <a href="{{ $pets->nextPageUrl() }}" class="px-4 py-2 text-sm font-medium text-[--color-foreground] bg-white border border-gray-300 rounded-lg hover:bg-[--color-muted] hover:border-[--color-border] transition-colors">
                                        Next →
                                    </a>
                                @else
                                    <span class="px-4 py-2 text-sm font-medium text-[--color-muted-foreground] bg-[--color-muted] border border-gray-300 rounded-lg cursor-not-allowed">
                                        Next →
                                    </span>
                                @endif
                            </nav>
                        </div>

                        {{-- Pagination Info --}}
                        <div class="mt-4 text-center text-sm text-[--color-muted-foreground]">
                            Showing {{ $pets->firstItem() }} to {{ $pets->lastItem() }} of {{ $pets->total() }} dog breeds
                        </div>
                    @endif
                </div>
            </section>
        </main>

    </div>
@endsection
