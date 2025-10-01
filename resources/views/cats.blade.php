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
                                    <img src="{{ $pets->image ? asset('storage/' . $pets->image) : '/placeholder.svg?height=600&width=600' }}"
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
            </div>
        </section>
    </main>


</div>
@endsection
