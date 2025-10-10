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
</style>
@endpush

@section('content')

    {{-- Main --}}
    <main class="flex-1" x-data="{ showGifModal: false }">
        <div class="container mx-auto px-4 py-6 md:px-6 md:py-12">

            <div class="grid gap-6 lg:grid-cols-2 lg:gap-12">
                {{-- Left: Image & Info Cards --}}
                <div class="space-y-4">
                    <div class="relative" x-data="{ 
                        activeTooltip: null, 
                        activeFact: '',
                        showAllHotspots: true,
                        // Activate a hotspot — show the bottom info panel (no floating tooltip)
                        setActive(feature, hotspotEl) {
                            this.activeTooltip = feature;
                            this.activeFact = hotspotEl.dataset.fact || '';
                        },
                        clearActive() { this.activeTooltip = null; this.activeFact = ''; }
                    }">
                        <!-- Keep image at its natural aspect ratio; tooltips are kept inside the container -->
                        <div class="rounded-lg overflow-hidden bg-[var(--color-muted)] relative inline-block w-full tooltip-image-container" x-ref="imageContainer">
                            <img src="{{ $pet->image ? $pet->image_url : '/placeholder.svg?height=600&width=600' }}"
                                 alt="{{ $pet->name }}"
                                 class="block w-full h-auto object-contain"
                                 style="z-index: 10; display:block;"
                                 @mouseenter="showAllHotspots = true"
                                 @mouseleave="showAllHotspots = true">

                            <!-- Interactive Tooltips -->
                            <div class="absolute inset-0" style="z-index: 15">
                                @php
                                    // Feature colors per feature key
                                    $featureColors = [
                                        'ears' => 'blue',
                                        'eyes' => 'green',
                                        'tail' => 'amber',
                                        'paws' => 'purple',
                                        'nose' => 'pink',
                                        'coat' => 'orange',
                                    ];

                                    // Default hotspots if none are defined in the database
                                    $defaultHotspots = [
                                        ['feature' => 'ears', 'position_x' => 50, 'position_y' => 15, 'width' => 64, 'height' => 40],
                                        ['feature' => 'eyes', 'position_x' => 50, 'position_y' => 30, 'width' => 64, 'height' => 32],
                                        ['feature' => 'tail', 'position_x' => 85, 'position_y' => 70, 'width' => 40, 'height' => 48],
                                        ['feature' => 'paws', 'position_x' => 30, 'position_y' => 85, 'width' => 32, 'height' => 32],
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
                                        if (!empty($funFacts)) {
                                            foreach ($funFacts as $funFact) {
                                                if (isset($funFact['feature']) && $funFact['feature'] === $feature) {
                                                    $fact = $funFact['fact'];
                                                    break;
                                                }
                                            }
                                        }

                                        // Default facts if none are provided in the database
                                        if (!$fact) {
                                            $defaultFacts = [
                                                'ears' => [
                                                    'Golden Retriever' => 'Their floppy ears help protect the ear canal from water and debris.',
                                                    'German Shepherd' => 'Their erect ears can rotate independently to locate sounds with precision.',
                                                    'default' => 'The ear shape is perfectly designed for their natural environment.'
                                                ],
                                                'eyes' => [
                                                    'Labrador Retriever' => 'Their eyes may sometimes appear red due to a reflective layer that helps them see better in dim light.',
                                                    'Husky' => 'They often have striking blue eyes due to a genetic trait that reduces melanin in the iris.',
                                                    'default' => 'Their eyes are specially adapted for their lifestyle and environment.'
                                                ],
                                                'tail' => [
                                                    'Border Collie' => 'Their tail acts as a rudder when making sharp turns while herding.',
                                                    'Beagle' => 'The white-tipped tail helped hunters spot them in tall grass.',
                                                    'default' => 'The tail helps with balance and is an important communication tool.'
                                                ],
                                                'paws' => [
                                                    'Newfoundland' => 'Their webbed feet make them excellent swimmers.',
                                                    'Greyhound' => 'Their padded feet absorb shock during high-speed runs.',
                                                    'default' => 'Their paw pads contain sweat glands that help regulate temperature.'
                                                ],
                                                'nose' => [
                                                    'default' => 'Their nose contains over 300 million scent receptors, compared to about 5–6 million in humans.'
                                                ],
                                                'coat' => [
                                                    'default' => 'Many breeds have a double coat: a soft undercoat for insulation and a protective outer layer.'
                                                ],
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

                                    <!-- {{ $feature }} Tooltip -->
                                    <div class="absolute hotspot-wrapper"
                                         style="top: {{ $hotspot['position_y'] }}%; left: {{ $hotspot['position_x'] }}%; transform: translate(-50%, -50%);"
                                         @mouseenter="setActive('{{ $feature }}', $el)"
                                         @mouseleave="clearActive()"
                                         @click="setActive('{{ $feature }}', $el)"
                                         data-fact="{{ e($fact) }}"
                                         data-x="{{ $hotspot['position_x'] }}"
                                         data-y="{{ $hotspot['position_y'] }}">
                                        <div class="cursor-pointer rounded-full border-2 tooltip-hotspot flex items-center justify-center backdrop-blur-sm text-pink-700 pulse-animation"
                                             style="width: {{ max(40, $hotspot['width']) }}px; height: {{ max(40, $hotspot['height']) }}px;">
                                            <span class="text-xs font-semibold select-none">{{ ucfirst($feature) }}</span>
                                        </div>

                                        <div x-show="activeTooltip === '{{ $feature }}'"
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 scale-95"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             class="absolute z-50 p-3 bg-white rounded-lg shadow-lg tooltip-content w-40 sm:w-48 text-sm"
                                             @if($hotspot['position_x'] < 30)
                                                 style="left: 100%; top: 0; margin-left: 12px;"
                                             @elseif($hotspot['position_x'] > 70)
                                                 style="right: 100%; top: 0; margin-right: 12px;"
                                             @elseif($hotspot['position_y'] < 30)
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

                                <!-- Small hint badge (kept) -->
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

                        <!-- Non-overlapping info panel below the image: shows the currently active hotspot fact -->
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

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('dogs') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-[color-mix(in_oklab,var(--color-primary)_8%,white)] hover:bg-[color-mix(in_oklab,var(--color-primary)_15%,white)] text-[var(--color-foreground)] border border-[var(--color-border)] transition hover-lift">← Back to Dog Breeds</a>
                    </div>
                </div>
            </div>

            {{-- GIF Modal (Refined for design consistency & responsiveness) --}}
            @if($pet->gif_url)
                <div
                    x-show="showGifModal"
                    x-transition.opacity
                    class="fixed inset-0 z-50 bg-black/60 flex items-center justify-center px-4 sm:px-6 md:px-8"
                    @click.self="showGifModal = false"
                    x-cloak
                >
                    <div class="relative bg-white rounded-xl shadow-xl max-w-3xl w-full overflow-hidden animate-fade-in-up">
                        <button
                            @click="showGifModal = false"
                            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition"
                            aria-label="Close"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>

                        <div class="p-4 sm:p-6">
                            <h2 class="text-lg font-semibold mb-4 text-center">
                                🎬 {{ $pet->name }} in Action
                            </h2>
                            <img src="{{ $pet->gif_url }}"
                                 alt="GIF of {{ $pet->name }}"
                                 class="w-full h-auto object-contain rounded-md max-h-[70vh] mx-auto transition" />
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </main>
</div>
@endsection
