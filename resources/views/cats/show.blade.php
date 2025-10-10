@extends('layouts.app')

@push('styles')
<style>
    .tooltip-hotspot {
        transition: all 0.2s ease;
    }
    .tooltip-content {
        /* Floating tooltips removed — use the bottom info panel for all viewports */
        display: none !important;
    }
    /* Responsive hotspot sizing so the interactive circles scale with viewport */
    .hotspot-wrapper .tooltip-hotspot {
        width: clamp(32px, 6vw, 64px) !important;
        height: clamp(32px, 6vw, 64px) !important;
    }
    .hotspot-wrapper .tooltip-hotspot span { font-size: clamp(10px, 1.6vw, 13px); }
    @keyframes pulse {
        0% { transform: scale(1); opacity: 0.6; }
        50% { transform: scale(1.05); opacity: 0.8; }
        100% { transform: scale(1); opacity: 0.6; }
    }
    .pulse-animation {
        animation: pulse 2s infinite;
    }

    /* Fade-in-up used by GIF modal */
    @keyframes fade-in-up {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fade-in-up 0.28s ease-out;
    }
    
    /* Cute paw pattern background */
    .paw-pattern {
        background-color: white;
        background-image: 
            radial-gradient(circle at 20% 20%, rgba(255, 182, 193, 0.15) 8px, transparent 8px),
            radial-gradient(circle at 80% 80%, rgba(255, 182, 193, 0.15) 8px, transparent 8px),
            radial-gradient(circle at 40% 60%, rgba(255, 182, 193, 0.15) 8px, transparent 8px),
            radial-gradient(circle at 70% 30%, rgba(255, 182, 193, 0.15) 8px, transparent 8px);
        background-size: 120px 120px;
        background-position: 0 0, 40px 40px, 80px 20px, 20px 80px;
    }
    
    .paw-icon {
        opacity: 0.08;
        position: absolute;
        font-size: 2rem;
        transform: rotate(-15deg);
    }
</style>
@endpush

@section('content')

<main class="flex-1" x-data="{ showGifModal: false }">
    <div class="container mx-auto px-4 py-6 md:px-6 md:py-12">

        <div class="grid gap-6 lg:grid-cols-2 lg:gap-12">
            {{-- Left: Image & Info Cards --}}
            <div class="space-y-4">
                <div class="relative" x-data="{ 
                    activeTooltip: null, 
                    activeFact: '',
                    showAllHotspots: true,
                    setActive(feature, hotspotEl) {
                        this.activeTooltip = feature;
                        this.activeFact = hotspotEl.dataset.fact || '';
                    },
                    clearActive() { this.activeTooltip = null; this.activeFact = ''; }
                }">
                    <div class="rounded-lg overflow-hidden bg-[var(--color-muted)] relative inline-block w-full tooltip-image-container" x-ref="imageContainer">
                        <img src="{{ $pet->image ? $pet->image_url : '/placeholder.svg?height=600&width=600' }}"
                             alt="{{ $pet->name }}"
                             class="block w-full h-auto object-contain"
                             style="z-index: 10; display:block;"
                             @mouseenter="showAllHotspots = true"
                             @mouseleave="showAllHotspots = true">

                        <div class="absolute inset-0" style="z-index: 15">
                            @php
                                $featureColors = [
                                    'ears' => 'blue',
                                    'eyes' => 'green',
                                    'tail' => 'amber',
                                    'paws' => 'purple',
                                    'nose' => 'pink',
                                    'coat' => 'orange',
                                ];

                                $defaultHotspots = [
                                    ['feature' => 'ears', 'position_x' => 50, 'position_y' => 15, 'width' => 64, 'height' => 40],
                                    ['feature' => 'eyes', 'position_x' => 50, 'position_y' => 30, 'width' => 64, 'height' => 32],
                                    ['feature' => 'tail', 'position_x' => 85, 'position_y' => 70, 'width' => 40, 'height' => 48],
                                    ['feature' => 'paws', 'position_x' => 30, 'position_y' => 85, 'width' => 32, 'height' => 32],
                                ];

                                $hotspots = $pet->hotspots ?? $defaultHotspots;
                                $funFacts = $pet->fun_facts ?? [];
                            @endphp

                            @foreach($hotspots as $hotspot)
                                @php
                                    $feature = $hotspot['feature'] ?? 'feature';
                                    $color = $featureColors[$feature] ?? 'gray';

                                    $fact = null;
                                    if (!empty($funFacts)) {
                                        foreach ($funFacts as $ff) {
                                            if (isset($ff['feature']) && $ff['feature'] === $feature) {
                                                $fact = $ff['fact'] ?? null;
                                                break;
                                            }
                                        }
                                    }

                                    if (!$fact) {
                                        $defaultFacts = [
                                            'ears' => ['default' => 'Cats use their ears to detect faint sounds and orient themselves.'],
                                            'eyes' => ['default' => 'Cats have excellent night vision thanks to a reflective layer behind the retina.'],
                                            'tail' => ['default' => 'A cat\'s tail helps with balance and communication.'],
                                            'paws' => ['default' => 'Cats have sensitive paw pads used for hunting and sensing terrain.'],
                                            'nose' => ['default' => 'A cat\'s nose is highly sensitive to scent.'],
                                            'coat' => ['default' => 'Coat patterns and density vary by breed and climate.'],
                                        ];

                                        if (isset($defaultFacts[$feature][$pet->name])) {
                                            $fact = $defaultFacts[$feature][$pet->name];
                                        } elseif (isset($defaultFacts[$feature]['default'])) {
                                            $fact = $defaultFacts[$feature]['default'];
                                        } else {
                                            $fact = 'Interesting facts about this ' . $feature . '.';
                                        }
                                    }
                                @endphp

                                <div class="absolute hotspot-wrapper"
                                     style="top: {{ $hotspot['position_y'] }}%; left: {{ $hotspot['position_x'] }}%; transform: translate(-50%, -50%);"
                                     @mouseenter="setActive('{{ $feature }}', $el)"
                                     @mouseleave="clearActive()"
                                     @click="setActive('{{ $feature }}', $el)"
                                     data-fact="{{ e($fact) }}"
                                     data-x="{{ $hotspot['position_x'] }}"
                                     data-y="{{ $hotspot['position_y'] }}">
                                    <div class="cursor-pointer rounded-full border-2 tooltip-hotspot flex items-center justify-center backdrop-blur-sm text-pink-700 pulse-animation"
                                         style="width: {{ max(40, $hotspot['width'] ?? 40) }}px; height: {{ max(40, $hotspot['height'] ?? 40) }}px;">
                                        <span class="text-xs font-semibold select-none">{{ ucfirst($feature) }}</span>
                                    </div>

                                    <div x-show="activeTooltip === '{{ $feature }}'"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         class="absolute z-50 p-3 bg-white rounded-lg shadow-lg tooltip-content w-40 sm:w-48 text-sm"
                                         @if(($hotspot['position_x'] ?? 50) < 30)
                                             style="left: 100%; top: 0; margin-left: 12px;"
                                         @elseif(($hotspot['position_x'] ?? 50) > 70)
                                             style="right: 100%; top: 0; margin-right: 12px;"
                                         @elseif(($hotspot['position_y'] ?? 50) < 30)
                                             style="bottom: 100%; left: 50%; transform: translateX(-50%); margin-bottom: 12px;"
                                         @else
                                             style="top: 100%; left: 50%; transform: translateX(-50%); margin-top: 12px;"
                                         @endif
                                    >
                                        <strong class="block mb-1 text-{{ $color }}-600">{{ $pet->name }}'s {{ ucfirst($feature) }}</strong>
                                        <p>{{ $fact }}</p>
                                    </div>
                                </div>
                            @endforeach

                            <div class="absolute bottom-2 left-2 bg-black/50 text-white text-xs px-2 py-1 rounded"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-show="showAllHotspots || activeTooltip !== null">
                                <span x-show="activeTooltip === null">Hover or tap the circles to learn about features</span>
                                <span x-show="activeTooltip !== null">Showing: <span x-text="activeTooltip"></span></span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 info-panel" x-show="activeTooltip" x-cloak>
                        <div class="p-3 bg-white rounded-lg shadow">
                            <strong class="block text-lg text-[--color-primary] mb-1">{{ $pet->name }}'s <span x-text="activeTooltip"></span></strong>
                            <p class="text-sm text-[--color-muted-foreground]" x-text="activeFact"></p>
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

                    @if($pet->gif_url)
                        <div class="mt-2">
                            <button
                                @click="showGifModal = true"
                                class="inline-flex items-center px-4 py-2 bg-[var(--color-primary)] text-white text-sm font-medium rounded-md hover:bg-[color-mix(in_oklab,var(--color-primary)_90%,black)] transition">
                                🎬 View Fun GIF
                            </button>
                        </div>
                    @endif
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

                {{-- Characteristics (stars + short descriptions) --}}
                <div class="space-y-6 pt-4">
                    @php
                        $characteristics = [
                            'Friendliness' => $pet->friendliness,
                            'Trainability' => $pet->trainability,
                            'Exercise Needs' => $pet->exerciseNeeds,
                            'Grooming' => $pet->grooming
                        ];

                        $characteristicDescriptions = [
                            'Friendliness' => 'How sociable the breed is with people and other animals (higher = very friendly).',
                            'Trainability' => 'How easy the breed is to train and respond to commands (higher = very trainable).',
                            'Exercise Needs' => 'Approximate daily activity needs (higher = needs more exercise).',
                            'Grooming' => 'Amount of grooming & coat maintenance required (higher = more grooming).',
                        ];
                    @endphp

                    @foreach($characteristics as $label => $value)
                        @php
                            $value = (int) $value; // ensure int 0-5
                            $description = $characteristicDescriptions[$label] ?? '';
                        @endphp

                        <div>
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <span class="text-sm font-medium block">{{ $label }}</span>
                                    <p class="text-xs text-[--color-muted-foreground] mt-1">{{ $description }}</p>
                                </div>

                                <div class="ml-4 flex items-center" aria-hidden="true">
                                    {{-- Stars (visual) --}}
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $value)
                                            <svg class="h-5 w-5 text-yellow-400 mr-1" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.13 3.478a1 1 0 00.95.69h3.654c.969 0 1.371 1.24.588 1.81l-2.958 2.15a1 1 0 00-.364 1.118l1.13 3.478c.3.921-.755 1.688-1.54 1.118l-2.958-2.15a1 1 0 00-1.176 0l-2.958 2.15c-.784.57-1.838-.197-1.539-1.118l1.13-3.478a1 1 0 00-.364-1.118L2.38 8.905c-.783-.57-.38-1.81.588-1.81h3.654a1 1 0 00.95-.69l1.13-3.478z"/></svg>
                                        @else
                                            <svg class="h-5 w-5 text-gray-300 mr-1" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.13 3.478a1 1 0 00.95.69h3.654c.969 0 1.371 1.24.588 1.81l-2.958 2.15a1 1 0 00-.364 1.118l1.13 3.478c.3.921-.755 1.688-1.54 1.118l-2.958-2.15a1 1 0 00-1.176 0l-2.958 2.15c-.784.57-1.838-.197-1.539-1.118l1.13-3.478a1 1 0 00-.364-1.118L2.38 8.905c-.783-.57-.38-1.81.588-1.81h3.654a1 1 0 00.95-.69l1.13-3.478z"/></svg>
                                        @endif
                                    @endfor
                                </div>
                            </div>

                            {{-- Accessible textual fallback for screen readers --}}
                            <div class="sr-only">{{ $label }}: {{ $value }} out of 5.</div>
                        </div>
                    @endforeach
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('cats') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-[color-mix(in_oklab,var(--color-primary)_8%,white)] hover:bg-[color-mix(in_oklab,var(--color-primary)_15%,white)] text-[var(--color-foreground)] border border-[var(--color-border)] transition hover-lift">← Back to Cat Breeds</a>
                </div>
            </div>
        </div>

        {{-- GIF Modal --}}
        @if($pet->gif_url)
            <div
                x-show="showGifModal"
                x-transition.opacity
                class="fixed inset-0 z-50 bg-black/60 flex items-center justify-center px-4 sm:px-6 md:px-8"
                @click.self="showGifModal = false"
                x-cloak
            >
                <div class="relative w-full mx-auto rounded-2xl shadow-2xl overflow-hidden bg-white animate-fade-in-up
                            max-w-[min(1100px,calc(100vw-96px))] max-h-[90vh]">
                    <!-- Decorative paws scattered throughout -->
                    <div class="paw-icon" style="top: 8%; left: 5%;">🐾</div>
                    <div class="paw-icon" style="top: 12%; right: 8%; transform: rotate(25deg);">🐾</div>
                    <div class="paw-icon" style="top: 25%; left: 3%; transform: rotate(-45deg);">🐾</div>
                    <div class="paw-icon" style="top: 35%; right: 12%; transform: rotate(10deg);">🐾</div>
                    <div class="paw-icon" style="top: 45%; left: 8%; transform: rotate(35deg);">🐾</div>
                    <div class="paw-icon" style="top: 55%; right: 5%; transform: rotate(-20deg);">🐾</div>
                    <div class="paw-icon" style="top: 18%; left: 15%; transform: rotate(60deg); font-size: 1.5rem;">🐾</div>
                    <div class="paw-icon" style="top: 42%; right: 18%; transform: rotate(-35deg); font-size: 1.5rem;">🐾</div>
                    <div class="paw-icon" style="bottom: 15%; left: 10%; transform: rotate(-45deg);">🐾</div>
                    <div class="paw-icon" style="bottom: 25%; right: 15%; transform: rotate(15deg);">🐾</div>
                    <div class="paw-icon" style="bottom: 35%; left: 5%; transform: rotate(45deg);">🐾</div>
                    <div class="paw-icon" style="bottom: 8%; right: 8%; transform: rotate(-10deg);">🐾</div>
                    <div class="paw-icon" style="bottom: 45%; left: 12%; transform: rotate(20deg); font-size: 1.5rem;">🐾</div>
                    <div class="paw-icon" style="bottom: 18%; right: 20%; transform: rotate(-55deg); font-size: 1.2rem;">🐾</div>
                    <div class="paw-icon" style="top: 65%; left: 4%; transform: rotate(50deg); font-size: 1.8rem;">🐾</div>
                    <div class="paw-icon" style="top: 75%; right: 10%; transform: rotate(-15deg); font-size: 1.3rem;">🐾</div>
                    <div class="paw-icon" style="top: 5%; left: 25%; transform: rotate(30deg); font-size: 1.4rem;">🐾</div>
                    <div class="paw-icon" style="top: 28%; right: 22%; transform: rotate(-40deg); font-size: 1.6rem;">🐾</div>
                    <div class="paw-icon" style="bottom: 5%; left: 18%; transform: rotate(65deg); font-size: 1.7rem;">🐾</div>
                    <div class="paw-icon" style="bottom: 52%; right: 6%; transform: rotate(-25deg); font-size: 1.1rem;">🐾</div>
                    <div class="paw-icon" style="top: 82%; left: 22%; transform: rotate(15deg); font-size: 1.9rem;">🐾</div>
                    <div class="paw-icon" style="top: 92%; right: 25%; transform: rotate(-50deg); font-size: 1.5rem;">🐾</div>
                    <div class="paw-icon" style="top: 3%; right: 18%; transform: rotate(40deg); font-size: 1.3rem;">🐾</div>
                    <div class="paw-icon" style="top: 20%; left: 9%; transform: rotate(-30deg); font-size: 1.6rem;">🐾</div>
                    <div class="paw-icon" style="top: 32%; right: 4%; transform: rotate(55deg); font-size: 1.2rem;">🐾</div>
                    <div class="paw-icon" style="top: 48%; left: 2%; transform: rotate(-60deg); font-size: 1.4rem;">🐾</div>
                    <div class="paw-icon" style="top: 60%; right: 14%; transform: rotate(20deg); font-size: 1.7rem;">🐾</div>
                    <div class="paw-icon" style="top: 70%; left: 18%; transform: rotate(-25deg); font-size: 1.1rem;">🐾</div>
                    <div class="paw-icon" style="top: 88%; right: 12%; transform: rotate(45deg); font-size: 1.5rem;">🐾</div>
                    <div class="paw-icon" style="bottom: 3%; left: 8%; transform: rotate(-40deg); font-size: 1.8rem;">🐾</div>
                    <div class="paw-icon" style="bottom: 12%; right: 22%; transform: rotate(30deg); font-size: 1.3rem;">🐾</div>
                    <div class="paw-icon" style="bottom: 28%; left: 20%; transform: rotate(-50deg); font-size: 1.6rem;">🐾</div>
                    <div class="paw-icon" style="bottom: 40%; right: 10%; transform: rotate(60deg); font-size: 1.2rem;">🐾</div>
                    <div class="paw-icon" style="bottom: 58%; left: 7%; transform: rotate(-15deg); font-size: 1.4rem;">🐾</div>
                    <div class="paw-icon" style="top: 14%; left: 28%; transform: rotate(35deg); font-size: 1.5rem;">🐾</div>
                    <div class="paw-icon" style="top: 38%; right: 28%; transform: rotate(-45deg); font-size: 1.3rem;">🐾</div>
                    <div class="paw-icon" style="top: 52%; left: 24%; transform: rotate(50deg); font-size: 1.7rem;">🐾</div>
                    <div class="paw-icon" style="bottom: 22%; right: 2%; transform: rotate(-35deg); font-size: 1.1rem;">🐾</div>
                    <div class="paw-icon" style="bottom: 48%; left: 16%; transform: rotate(25deg); font-size: 1.6rem;">🐾</div>
                    <div class="paw-icon" style="bottom: 62%; right: 24%; transform: rotate(-20deg); font-size: 1.4rem;">🐾</div>
                    
                    <button
                        @click="showGifModal = false"
                        class="absolute top-3 right-3 z-30 inline-flex items-center justify-center h-10 w-10 rounded-full bg-white hover:bg-gray-100 text-gray-700 shadow-lg transition"
                        aria-label="Close"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    <div class="flex flex-col sm:flex-row gap-4 p-3 sm:p-5 h-full">
                        <div class="flex-1 flex items-center justify-center bg-gray-100 rounded-md p-2 overflow-auto">
                            <img
                                src="{{ $pet->gif_url }}"
                                alt="GIF of {{ $pet->name }}"
                                class="max-w-full w-auto max-h-[80vh] rounded-md object-contain"
                            />
                        </div>

                        <div class="sm:w-64 flex flex-col justify-center gap-3 text-center sm:text-left bg-white/95 backdrop-blur-sm p-4 rounded-lg relative z-10 overflow-hidden">
                            <!-- Decorative paws for text panel -->
                            <div class="paw-icon" style="top: 5%; right: 10%; font-size: 1.5rem;">🐾</div>
                            <div class="paw-icon" style="top: 15%; left: 8%; font-size: 1.2rem; transform: rotate(45deg);">🐾</div>
                            <div class="paw-icon" style="top: 25%; right: 15%; font-size: 1rem; transform: rotate(-30deg);">🐾</div>
                            <div class="paw-icon" style="top: 35%; left: 5%; font-size: 1.3rem; transform: rotate(60deg);">🐾</div>
                            <div class="paw-icon" style="top: 45%; right: 8%; font-size: 1.4rem; transform: rotate(-15deg);">🐾</div>
                            <div class="paw-icon" style="top: 55%; left: 12%; font-size: 1.1rem; transform: rotate(25deg);">🐾</div>
                            <div class="paw-icon" style="top: 65%; right: 18%; font-size: 1.6rem; transform: rotate(-45deg);">🐾</div>
                            <div class="paw-icon" style="top: 75%; left: 6%; font-size: 1.2rem; transform: rotate(35deg);">🐾</div>
                            <div class="paw-icon" style="bottom: 10%; left: 5%; transform: rotate(-25deg); font-size: 1.2rem;">🐾</div>
                            <div class="paw-icon" style="bottom: 20%; right: 12%; transform: rotate(50deg); font-size: 1.5rem;">🐾</div>
                            <div class="paw-icon" style="bottom: 30%; left: 15%; transform: rotate(-10deg); font-size: 1.3rem;">🐾</div>
                            <div class="paw-icon" style="bottom: 5%; right: 5%; transform: rotate(40deg); font-size: 1rem;">🐾</div>
                            <div class="paw-icon" style="top: 10%; left: 20%; transform: rotate(-55deg); font-size: 1.4rem;">🐾</div>
                            <div class="paw-icon" style="bottom: 15%; left: 22%; transform: rotate(20deg); font-size: 1.7rem;">🐾</div>
                            
                            <h2 class="text-lg font-semibold text-gray-900 relative z-20">🎬 {{ $pet->name }} in Action</h2>
                            <p class="text-sm text-gray-600 relative z-20">A short cute clip of {{ $pet->name }}. Click outside or the close button to dismiss.</p>

                            <div class="flex justify-center sm:justify-start gap-2 mt-2 relative z-20">
                                <button @click="showGifModal = false" class="px-3 py-2 rounded-md bg-[var(--color-primary)] text-white text-sm hover:opacity-90 transition">Close</button>
                                @if($pet->image_url)
                                    <a href="{{ $pet->image_url }}" target="_blank" class="px-3 py-2 rounded-md border border-gray-300 text-sm hover:bg-gray-50 transition">Open image</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</main>

@endsection