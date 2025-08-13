@extends('layouts.app')


@section('content')


    {{-- Main --}}
    <main class="flex-1">
        <div class="container px-4 py-6 md:px-6 md:py-12">


            <div class="grid gap-6 lg:grid-cols-2 lg:gap-12">
                {{-- Left: Image & Info Cards --}}
                <div class="space-y-4">
                    <div class="relative">
                        <div class="aspect-square rounded-lg overflow-hidden bg-gray-100">
                            <img src="{{ asset('storage/' . $pet->image) ?? '/placeholder.svg?height=600&width=600' }}"
                                 alt="{{ $pet->name }}"
                                 class="object-cover w-full h-full">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                        <div class="p-3 border rounded">
                            <p class="text-sm text-gray-500">Size</p>
                            <p class="text-base font-bold">{{ $pet->size }}</p>
                        </div>
                        <div class="p-3 border rounded">
                            <p class="text-sm text-gray-500">Lifespan</p>
                            <p class="text-base font-bold">{{ $pet->lifespan }} years</p>
                        </div>
                        <div class="p-3 border rounded">
                            <p class="text-sm text-gray-500">Energy</p>
                            <p class="text-base font-bold">{{ $pet->energy }}</p>
                        </div>
                    </div>
                </div>

                {{-- Right: Tabs & Details --}}
                <div class="space-y-6">
                    <div>
                        <h1 class="text-3xl font-bold">{{ $pet->name }}</h1>
                        <p class="text-gray-500">{{ $pet->temperament }}</p>
                    </div>

                    {{-- Overview --}}
                    <div class="space-y-4 pt-4">
                        <p>{{ $pet->description }}</p>
                        @if($pet->colors)
                            <div>
                                <h3 class="font-medium mb-2">Common Colors</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($pet->colors as $color)
                                        <div class="px-3 py-1 bg-gray-100 rounded-full text-sm">{{ $color }}</div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Characteristics --}}
                    <div class="space-y-4 pt-4">
                        @php
                            $characteristics = [
                                'Friendliness' => $pet->friendliness,
                                'Trainability' => $pet->trainability,
                                'Exercise Needs' => $pet->exercise_needs,
                                'Grooming' => $pet->grooming
                            ];
                        @endphp

                        @foreach($characteristics as $label => $value)
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium">{{ $label }}</span>
                                    <span class="text-sm text-gray-500">{{ $value }}/5</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-primary h-2.5 rounded-full" style="width: {{ ($value / 5) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-4">
                        <button class="px-4 py-2 border border-black rounded-md bg-black text-white hover:bg-gray-700 hover:text-white transition">Add to Comparison</button>
                        <a href="{{ route('dogs') }}" class="px-4 py-2 border border-black rounded-md bg-black text-white hover:bg-gray-700 hover:text-white transition">
                            Return to Dog Breeds
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
