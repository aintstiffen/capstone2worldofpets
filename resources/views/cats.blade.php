@extends('layouts.app')

@section('content')
<div class="flex flex-col min-h-screen">


    <!-- Hero Section -->
    <main class="flex-1">
        <section class="w-full py-12 md:py-24 lg:py-32 bg-muted/30">
            <div class="container mx-auto px-4 md:px-6 text-center">
                <h1 class="text-3xl font-bold tracking-tighter sm:text-4xl md:text-5xl">Cat Breeds</h1>
                <p class="max-w-[900px] mx-auto text-muted-foreground md:text-xl/relaxed lg:text-base/relaxed xl:text-xl/relaxed">
                    Explore detailed profiles of the most popular cat breeds in the Philippines.
                </p>
            </div>
        </section>

        <!-- Dog Breed Cards -->
        <section class="w-full py-12 md:py-24 lg:py-32">
            <div class="container mx-auto px-4 md:px-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

                   @if(isset($petss) && $petss->count() > 0)
                        @foreach($petss as $pets)
                        <a href="{{ route('cats.show', $pets->slug) }}" class="group">
                            <div class="overflow-hidden border rounded-lg transition-all hover:shadow-lg">
                                <div class="aspect-square relative">
                                    <img src="{{ $pets->image ? $pets->image_url : '/placeholder.svg?height=600&width=600' }}"
                                         alt="{{ $pets->name }}"
                                         class="object-cover w-full h-full transition-transform group-hover:scale-105">
                                </div>
                                <div class="p-4">
                                    <h2 class="font-bold text-lg truncate">{{ $pets->name }}</h2>
                                    <p class="text-sm text-muted-foreground truncate">{{ $pets->temperament ?? 'N/A' }}</p>
                                </div>
                                <hr>
                                <div class="p-4 flex justify-between items-center text-sm">
                                    <div class="flex items-center gap-2">
                                        <span>{{ $pets->size ?? 'N/A' }}</span>
                                        <span class="text-muted-foreground">•</span>
                                        <span class="text-muted-foreground">{{ $pets->lifespan ?? 'N/A' }} years</span>
                                    </div>
                                    <span class="rounded-full border p-1">➜</span>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    @else
                        <!-- Show message when no dog breeds are found -->
                        <div class="col-span-full text-center py-12">
                            <h3 class="text-xl font-semibold mb-2">No Cat Breeds Found</h3>
                            <p class="text-muted-foreground">Please add some dog breeds to the database.</p>
                        </div>
                    @endif

                    

                    

                </div>
                
                <!-- Pagination Links -->
                @if($petss->hasPages())
                    <div class="mt-12 flex justify-center">
                        <nav class="flex items-center gap-2">
                            {{-- Previous Page Link --}}
                            @if ($petss->onFirstPage())
                                <span class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed">
                                    ← Previous
                                </span>
                            @else
                                <a href="{{ $petss->previousPageUrl() }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-colors">
                                    ← Previous
                                </a>
                            @endif

                            {{-- Page Numbers --}}
                            <div class="flex items-center gap-1">
                                @foreach ($petss->getUrlRange(1, $petss->lastPage()) as $page => $url)
                                    @if ($page == $petss->currentPage())
                                        <span class="px-4 py-2 text-sm font-bold text-white bg-blue-600 border border-blue-600 rounded-lg">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-colors">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>

                            {{-- Next Page Link --}}
                            @if ($petss->hasMorePages())
                                <a href="{{ $petss->nextPageUrl() }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-colors">
                                    Next →
                                </a>
                            @else
                                <span class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed">
                                    Next →
                                </span>
                            @endif
                        </nav>
                    </div>

                    {{-- Pagination Info --}}
                    <div class="mt-4 text-center text-sm text-gray-600">
                        Showing {{ $petss->firstItem() }} to {{ $petss->lastItem() }} of {{ $petss->total() }} cat breeds
                    </div>
                @endif
            </div>
        </section>
    </main>


</div>
@endsection
