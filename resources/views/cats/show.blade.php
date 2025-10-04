@extends('layouts.app')

@push('styles')
<style>
    /* Keep this minimal so Tailwind classes control visuals */
    .tooltip-hotspot { 
        transition: all 0.2s ease; 
        position: absolute; 
        z-index: 20; 
        border-radius: 9999px; 
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .tooltip-content {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 30;
        background-color: white;
        padding: 8px 12px;
        border-radius: 6px;
    }
</style>
@endpush

@section('content')


    {{-- Main --}}
    <main class="flex-1">
        <div class="container mx-auto px-4 py-6 md:px-6 md:py-12">


            <div class="grid gap-6 lg:grid-cols-2 lg:gap-12">
                {{-- Left: Image & Info Cards --}}
                <div class="space-y-4">
                    <div class="relative" x-data="{ activeTooltip: null, showAllHotspots: true }">
                        <div class="aspect-square rounded-lg overflow-hidden bg-[var(--color-muted)] relative">
                            <img src="{{ $pet->image ? $pet->image_url : '/placeholder.svg?height=600&width=600' }}"
                                 alt="{{ $pet->name }}"
                                 class="object-cover w-full h-full"
                                 style="z-index: 10"
                                 @mouseenter="showAllHotspots = true"
                                 @mouseleave="showAllHotspots = true">

                            <!-- Interactive Tooltips -->
                            <div class="absolute inset-0" style="z-index: 15"> <!-- Add z-index here -->
                                @php
                                    // Default feature colors
                                    $featureColors = [
                                        'ears' => 'blue',
                                        'eyes' => 'green',
                                        'tail' => 'amber',
                                        'paws' => 'purple',
                                        'nose' => 'pink',
                                        'coat' => 'orange'
                                    ];
                                    
                                    // Default hotspots if none are defined in the database
                                    $defaultHotspots = [
                                        ['feature' => 'ears', 'position_x' => 50, 'position_y' => 15, 'width' => 64, 'height' => 40],
                                        ['feature' => 'eyes', 'position_x' => 50, 'position_y' => 30, 'width' => 64, 'height' => 32],
                                        ['feature' => 'tail', 'position_x' => 85, 'position_y' => 70, 'width' => 40, 'height' => 48],
                                        ['feature' => 'paws', 'position_x' => 30, 'position_y' => 85, 'width' => 32, 'height' => 32]
                                    ];
                                    
                                    // Use the hotspots from the database if available, otherwise use defaults
                                    $hotspots = $pet->hotspots ?? $defaultHotspots;
                                    
                                    // Get fun facts from database or use defaults
                                    $funFacts = $pet->fun_facts ?? [];
                                @endphp

                                @foreach($hotspots as $hotspot)
                                @php
                                    $feature = $hotspot['feature'];
                                    $color = $featureColors[$feature] ?? 'gray';
                                    
                                    // Find matching fact if it exists in the database
                                    $fact = null;
                                    if(!empty($funFacts)) {
                                        foreach($funFacts as $funFact) {
                                            if(isset($funFact['feature']) && $funFact['feature'] === $feature) {
                                                $fact = $funFact['fact'];
                                                break;
                                            }
                                        }
                                    }
                                    
                                    // Default facts if none are provided in the database
                                    if(!$fact) {
                                        $defaultFacts = [
                                            'ears' => [
                                                'Scottish Fold' => 'Their folded ears are caused by a natural dominant gene mutation that affects cartilage.',
                                                'Maine Coon' => 'Their tufted ears help protect from cold weather and snow.',
                                                'default' => 'Their ears contain 32 muscles, allowing for precise movements to locate sounds.'
                                            ],
                                            'eyes' => [
                                                'Siamese' => 'Their striking blue eyes are caused by a form of partial albinism.',
                                                'Sphynx' => 'Their large eyes help compensate for having no fur to gather sensory information.',
                                                'default' => 'Cats have a reflective layer behind their retinas called the tapetum lucidum, allowing them to see in one-sixth the light humans need.'
                                            ],
                                            'tail' => [
                                                'Manx' => 'Their tailless trait is caused by a natural genetic mutation.',
                                                'Japanese Bobtail' => 'Their short "bunny" tail consists of one or more curves, kinks, or angles.',
                                                'default' => 'A cat\'s tail contains about 10% of the bones in their body and helps with balance.'
                                            ],
                                            'paws' => [
                                                'Polydactyl' => 'They have extra toes, sometimes giving the appearance of "mittens" or "snowshoes".',
                                                'Sphynx' => 'Without fur, their paw pads leave sweat marks, as this is where cats primarily sweat.',
                                                'default' => 'Cats walk directly on their toes (digitigrade), which helps with hunting stealth.'
                                            ],
                                            'nose' => [
                                                'default' => 'A cat\'s nose print is unique, like a human fingerprint, with no two alike.'
                                            ],
                                            'coat' => [
                                                'default' => 'Their coat has specialized papillae that help with grooming and sensory perception.'
                                            ]
                                        ];
                                        
                                        // Check if we have a breed-specific fact
                                        if (isset($defaultFacts[$feature][$pet->name])) {
                                            $fact = $defaultFacts[$feature][$pet->name];
                                        } 
                                        // Otherwise use the default fact for this feature
                                        elseif (isset($defaultFacts[$feature]['default'])) {
                                            $fact = $defaultFacts[$feature]['default'];
                                        }
                                        else {
                                            $fact = 'Interesting facts about this ' . $feature . '.';
                                        }
                                    }
                                @endphp
                                
                          <!-- {{ $feature }} Tooltip -->
                          <div class="absolute" 
                              style="top: {{ $hotspot['position_y'] }}%; left: {{ $hotspot['position_x'] }}%; transform: translate(-50%, -50%);"
                              @mouseenter="activeTooltip = '{{ $feature }}'"
                              @mouseleave="activeTooltip = null">
                             <div class="cursor-pointer rounded-full border-2 tooltip-hotspot flex items-center justify-center backdrop-blur-sm text-pink-700 pulse-animation"
                                 style="width: {{ max(56, $hotspot['width']) }}px; height: {{ max(56, $hotspot['height']) }}px;">
                                 <span class="text-xs font-semibold select-none">{{ ucfirst($feature) }}</span>
                             </div>
                                    <div x-show="activeTooltip === '{{ $feature }}'" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         class="absolute z-10 p-3 bg-white rounded-lg shadow-lg tooltip-content w-48 text-sm"
                                         @if($hotspot['position_x'] < 30)
                                             style="left: 100%; top: 0; margin-left: 8px;"
                                         @elseif($hotspot['position_x'] > 70)
                                             style="right: 100%; top: 0; margin-right: 8px;"
                                         @elseif($hotspot['position_y'] < 30)
                                             style="bottom: 100%; left: 50%; transform: translateX(-50%); margin-bottom: 8px;"
                                         @else
                                             style="top: 100%; left: 50%; transform: translateX(-50%); margin-top: 8px;"
                                         @endif
                                        >
                                        <strong class="block mb-1 text-{{ $color }}-600">{{ $pet->name }}'s {{ ucfirst($feature) }}</strong>
                                        <p>{{ $fact }}</p>
                                    </div>
                                </div>
                                @endforeach
                                
                                <!-- Hint text for users -->
                                <div class="absolute bottom-2 left-2 bg-black/50 text-white text-xs px-2 py-1 rounded"
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-show="showAllHotspots || activeTooltip !== null">
                                    <span x-show="activeTooltip === null">Hover the pink circles to learn about features</span>
                                    <span x-show="activeTooltip !== null">Interesting facts about this {{ $pet->name }}'s <span x-text="activeTooltip"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                        <div class="p-3 border rounded surface-card">
                            <p class="text-sm text-[--color-muted-foreground]">Size</p>
                            <p class="text-base font-bold">{{ $pet->size }}</p>
                        </div>
                        <div class="p-3 border rounded surface-card">
                            <p class="text-sm text-[--color-muted-foreground]">Lifespan</p>
                            <p class="text-base font-bold">{{ $pet->lifespan }} years</p>
                        </div>
                        <div class="p-3 border rounded surface-card">
                            <p class="text-sm text-[--color-muted-foreground]">Energy</p>
                            <p class="text-base font-bold">{{ $pet->energy }}</p>
                        </div>
                    </div>
                </div>

                {{-- Right: Tabs & Details --}}
                <div class="space-y-6">
                    <div>
                        <h1 class="text-3xl font-bold">{{ $pet->name }}</h1>
                        <p class="text-[--color-muted-foreground]">{{ $pet->temperament }}</p>
                    </div>

                    {{-- Overview --}}
                    <div class="space-y-4 pt-4">
                        <p>{{ $pet->description }}</p>
                        @if($pet->colors)
                            <div>
                                <h3 class="font-medium mb-2">Common Colors</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($pet->colors as $color)
                                        <div class="px-3 py-1 rounded-full text-sm" style="background-color: color-mix(in oklab, var(--color-secondary) 12%, white); color: color-mix(in oklab, var(--color-secondary) 50%, black);">{{ $color }}</div>
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
                                'Exercise Needs' => $pet->exerciseNeeds,
                                'Grooming' => $pet->grooming
                            ];
                        @endphp

                        @foreach($characteristics as $label => $value)
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium">{{ $label }}</span>
                                    <span class="text-sm text-[--color-muted-foreground]">{{ $value }}/5</span>
                                </div>
                                <div class="w-full rounded-full h-2.5" style="background-color: color-mix(in oklab, var(--color-muted) 60%, white);">
                                    <div class="bg-primary h-2.5 rounded-full" style="width: {{ ($value / 5) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-4">
                        <button class="px-4 py-2 rounded-md text-white bg-[--color-primary] hover:bg-[--color-primary-dark] transition hover-lift">Add to Comparison</button>
                        <a href="{{ route('cats') }}" class="px-4 py-2 rounded-md text-white bg-[--color-primary] hover:bg-[--color-primary-dark] transition hover-lift">
                            Return to Cat Breeds
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
