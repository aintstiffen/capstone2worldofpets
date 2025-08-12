@extends('layouts.app')

@section('title', 'Cat Breeds')

@section('content')
<div class="flex flex-col min-h-screen">

    

    {{-- Hero Section --}}
    <main class="flex-1">
        <section class="w-full py-12 md:py-24 lg:py-32 bg-muted/30">
            <div class="container px-4 md:px-6 text-center space-y-4">
                <div class="space-y-2">
                    <h1 class="text-3xl font-bold tracking-tighter sm:text-4xl md:text-5xl">Cat Breeds</h1>
                    <p class="max-w-[900px] mx-auto text-muted-foreground md:text-xl/relaxed lg:text-base/relaxed xl:text-xl/relaxed">
                        Explore detailed profiles of the most popular cat breeds in the Philippines.
                    </p>
                </div>
            </div>
        </section>

        {{-- Cat Cards Section --}}
        <section class="w-full py-12 md:py-24 lg:py-32">
            <div class="container px-4 md:px-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    
                    {{-- Uncomment when you have backend data --}}
                    {{--
                    @foreach($catBreeds as $breed)
                        <a href="{{ url('/cats/' . $breed->slug) }}" class="group">
                            <div class="bg-white border rounded-lg overflow-hidden transition-all hover:shadow-lg">
                                <div class="aspect-square relative">
                                    <img src="{{ $breed->image ?? '/placeholder.svg?height=300&width=300' }}"
                                         alt="{{ $breed->name }}"
                                         class="object-cover w-full h-full transition-transform group-hover:scale-105">
                                </div>
                                <div class="p-4">
                                    <h2 class="text-lg font-semibold line-clamp-1">{{ $breed->name }}</h2>
                                    <p class="text-sm text-muted-foreground line-clamp-1">{{ $breed->temperament }}</p>
                                </div>
                                <div class="border-t p-4 flex justify-between items-center">
                                    <div class="flex items-center gap-2 text-sm">
                                        <span>{{ $breed->size }}</span>
                                        <span class="text-muted-foreground">•</span>
                                        <span class="text-muted-foreground">{{ $breed->lifespan }} years</span>
                                    </div>
                                    <button class="p-2 rounded-full hover:bg-gray-100">
                                        →
                                    </button>
                                </div>
                            </div>
                        </a>
                    @endforeach
                    --}}
                                        <!-- Static placeholders to avoid errors -->
                    <a href="#" class="group">
                        <div class="overflow-hidden border rounded-lg transition-all hover:shadow-lg">
                            <div class="aspect-square relative">
                                <img src="https://placehold.co/300x300" 
                                     alt="Sample Dog" 
                                     class="object-cover w-full h-full transition-transform group-hover:scale-105">
                            </div>
                            <div class="p-4">
                                <h2 class="font-bold text-lg truncate">Sample Dog</h2>
                                <p class="text-sm text-muted-foreground truncate">Friendly and loyal</p>
                            </div>
                            <hr>
                            <div class="p-4 flex justify-between items-center text-sm">
                                <div class="flex items-center gap-2">
                                    <span>Medium</span>
                                    <span class="text-muted-foreground">•</span>
                                    <span class="text-muted-foreground">12 years</span>
                                </div>
                                <span class="rounded-full border p-1">➜</span>
                            </div>
                        </div>
                    </a>
                    
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
                        <li><a href="{{ url('/dogs') }}" class="text-muted-foreground hover:text-foreground">Dog Breeds</a></li>
                        <li><a href="{{ url('/cats') }}" class="text-muted-foreground hover:text-foreground">Cat Breeds</a></li>
                    </ul>
                </div>
                <div class="space-y-3">
                    <h3 class="text-sm font-medium">Tools</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ url('/assessment') }}" class="text-muted-foreground hover:text-foreground">Personality Assessment</a></li>
                        <li><a href="{{ url('/compare') }}" class="text-muted-foreground hover:text-foreground">Breed Comparison</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection
