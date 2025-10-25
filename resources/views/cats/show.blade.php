@extends('layouts.app')

@push('styles')
    <style>
        .tooltip-hotspot {
            transition: transform 220ms cubic-bezier(.2, .9, .2, 1), box-shadow 220ms ease, opacity 220ms ease;
            will-change: transform, box-shadow, opacity;
        }

        /* Allow floating tooltip pop-ups with smooth transitions. Controlled via Alpine x-show/x-transition. */
        .tooltip-content {
            display: block;
            /* visibility controlled by Alpine x-show */
            opacity: 0;
            transform: translateY(6px) scale(.98);
            transition: opacity 200ms ease, transform 200ms ease;
            pointer-events: none;
            /* disabled by default; enabled when .show is added */
            max-width: 270px;
            max-height: 180px;
            /* allow taller tooltip so content can scroll on mobile */
            overflow-y: auto;
            /* allow scrolling if needed */
            -webkit-overflow-scrolling: touch;
            font-size: clamp(12px, 1.3vw, 15px);
            padding: 12px 16px 12px 12px;
            margin: 8px 0 8px 0;
            box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.10);
            background: #fff;
            border-radius: 10px;
            word-break: break-word;
            z-index: 100;
        }

        @media (min-width: 1024px) {
            .tooltip-content {
                max-width: 320px !important;
                max-height: 200px;
                padding: 16px 20px 16px 16px;
                margin-right: 24px !important;
                border-radius: 12px;
            }
        }

        .tooltip-content[x-cloak] {
            display: none;
        }

        .tooltip-content.show {
            opacity: 1 !important;
            transform: none !important;
            /* Enable interaction so users can scroll the tooltip on touch devices and hover it on desktop */
            pointer-events: auto;
        }

        /* Custom simple scrollbar styling for modern browsers */
        .tooltip-content::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        .tooltip-content::-webkit-scrollbar-track {
            background: transparent;
        }

        .tooltip-content::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.12);
            border-radius: 999px;
        }

        /* Hover / tap visual affordances for hotspots */
        .tooltip-hotspot:hover,
        .tooltip-hotspot:focus {
            transform: scale(1.06);
            box-shadow: 0 10px 20px rgba(17, 24, 39, 0.12);
            opacity: 1;
        }

        .tooltip-hotspot:active {
            transform: scale(0.98);
        }

        /* Small ring pulse using pseudo-element (subtle) */
        .tooltip-hotspot::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 9999px;
            box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.12);
            transition: box-shadow 420ms ease;
            pointer-events: none;
        }

        .tooltip-hotspot:hover::after {
            box-shadow: 0 0 18px 6px rgba(59, 130, 246, 0.06);
        }

        /* Tooltip caret */
        .tooltip-arrow {
            width: 12px;
            height: 12px;
            background: white;
            position: absolute;
            transform: rotate(45deg);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.06);
        }

        .tooltip-arrow.top {
            bottom: -6px;
            left: 50%;
            transform: translateX(-50%) rotate(45deg);
        }

        .tooltip-arrow.bottom {
            top: -6px;
            left: 50%;
            transform: translateX(-50%) rotate(45deg);
        }

        .tooltip-arrow.left {
            right: -6px;
            top: 50%;
            transform: translateY(-50%) rotate(45deg);
        }

        .tooltip-arrow.right {
            left: -6px;
            top: 50%;
            transform: translateY(-50%) rotate(45deg);
        }

        /* Responsive hotspot sizing so the interactive circles scale with viewport */
        .hotspot-wrapper .tooltip-hotspot {
            width: clamp(32px, 6vw, 64px) !important;
            height: clamp(32px, 6vw, 64px) !important;
        }

        .hotspot-wrapper .tooltip-hotspot span {
            font-size: clamp(10px, 1.6vw, 13px);
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 0.6;
            }

            50% {
                transform: scale(1.05);
                opacity: 0.8;
            }

            100% {
                transform: scale(1);
                opacity: 0.6;
            }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        /* Fade-in-up used by GIF modal */
        @keyframes fade-in-up {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
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

        /* Gallery Carousel Styles */
        .gallery-carousel {
            position: relative;
            overflow: hidden;
        }

        .gallery-track {
            display: flex;
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: transform;
        }

        .gallery-slide {
            flex: 0 0 100%;
            min-width: 0;
        }

        @media (min-width: 640px) {
            .gallery-slide {
                flex: 0 0 50%;
            }
        }

        @media (min-width: 768px) {
            .gallery-slide {
                flex: 0 0 33.333333%;
            }
        }

        @media (min-width: 1024px) {
            .gallery-slide {
                flex: 0 0 25%;
            }
        }

        .gallery-image {
            aspect-ratio: 4/3;
            object-fit: cover;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: default;
            /* not clickable, only hover-zoom */
        }

        .gallery-image:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 10;
        }

        .carousel-btn:hover {
            background: white;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
            transform: translateY(-50%) scale(1.1);
        }

        .carousel-btn:active {
            transform: translateY(-50%) scale(0.95);
        }

        .carousel-btn.prev {
            left: 10px;
        }

        .carousel-btn.next {
            right: 10px;
        }

        .carousel-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 16px;
        }

        .carousel-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #d1d5db;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            padding: 0;
        }

        .carousel-dot:hover {
            background: #9ca3af;
            transform: scale(1.2);
        }

        .carousel-dot.active {
            background: var(--color-primary, #3b82f6);
            width: 24px;
            border-radius: 5px;
        }

        /* Lightbox Modal */
        .lightbox-modal {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(0, 0, 0, 0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .lightbox-image {
            max-width: 90vw;
            max-height: 80vh;
            width: auto;
            height: auto;
            object-fit: contain !important;
            border-radius: 8px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }

        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: white;
            border: none;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .lightbox-close:hover {
            transform: scale(1.1);
            background: #f3f4f6;
        }

        /* small preview card style; keep size consistent with color preview */
        .diet-preview {
            position: fixed;
            z-index: 60;
            width: 280px;
            /* keep same beautiful size */
            max-width: calc(100vw - 32px);
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            background: white;
            overflow: hidden;
            pointer-events: none;
            transform-origin: bottom center;
        }

        .diet-preview img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            display: block;
        }

        .diet-preview .label {
            padding: 8px 12px;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')

    <main class="flex-1" x-data="{
        showGifModal: false,
        lightboxOpen: false,
        lightboxImage: '',
        currentSlide: 0,
        totalSlides: {{ count($pet->gallery ?? []) }},
        // Number of slides visible at once (keeps parity with the CSS breakpoints above).
        get visibleSlides() {
            if (window.innerWidth >= 1024) return 4;
            if (window.innerWidth >= 768) return 3;
            if (window.innerWidth >= 640) return 2;
            return 1; // mobile: 1 slide per view
        },
        get maxSlideIndex() {
            return Math.max(0, this.totalSlides - this.visibleSlides);
        },
        nextSlide() {
            if (this.currentSlide < this.maxSlideIndex) {
                this.currentSlide++;
            }
        },
        prevSlide() {
            if (this.currentSlide > 0) {
                this.currentSlide--;
            }
        },
        goToSlide(index) {
            this.currentSlide = Math.min(index, this.maxSlideIndex);
        },
        openLightbox(url) {
            this.lightboxImage = url;
            this.lightboxOpen = true;
            document.body.style.overflow = 'hidden';
        },
        closeLightbox() {
            this.lightboxOpen = false;
            document.body.style.overflow = '';
        }
    }" @resize.window="currentSlide = Math.min(currentSlide, maxSlideIndex)">
        <div class="container mx-auto px-4 py-6 md:px-6 md:py-12">

            <div class="grid gap-6 lg:grid-cols-2 lg:gap-12">
                {{-- Left: Image & Info Cards --}}
                <div class="space-y-4">
                    <div class="relative" x-data="{
                        activeTooltip: null,
                        activeFact: '',
                        showAllHotspots: true,
                        tooltipStyle: {},
                        tooltipPlacement: null,
                        tooltipHovered: false,
                        setActive(feature, hotspotEl) {
                            this.activeTooltip = feature;
                            // read fact from the element dataset for accessibility
                            this.activeFact = hotspotEl.dataset.fact || '';
                    
                            // compute popup position relative to the image container
                            const container = this.$refs.imageContainer;
                            if (!container) return;
                            const containerRect = container.getBoundingClientRect();
                            const elRect = hotspotEl.getBoundingClientRect();
                    
                            // center point of hotspot relative to container
                            const cx = (elRect.left - containerRect.left) + (elRect.width / 2);
                            const cy = (elRect.top - containerRect.top) + (elRect.height / 2);
                    
                            // choose placement: left/right/top/bottom depending on where hotspot sits
                            const w = containerRect.width;
                            const h = containerRect.height;
                    
                            // Force tooltip into the right-center of the image container so it's always visible.
                            const padding = 12;
                            // Dynamically get tooltip width from CSS for desktop
                            let tooltipW = 320;
                            if (window.innerWidth >= 1024) tooltipW = 380;
                            let left;
                            if (w > (tooltipW + padding * 2)) {
                                left = w - tooltipW - padding - 32; // align inside right edge, extra margin for desktop
                            } else {
                                left = Math.max(padding, (w - tooltipW) / 2);
                            }
                    
                            // Clamp tooltip so it never overlaps the bottom edge (mobile and desktop)
                            let tooltipH = 180;
                            if (window.innerWidth >= 1024) tooltipH = 260;
                            let top = cy;
                            top = Math.max(padding, Math.min(top, h - tooltipH - padding));
                    
                            // Prevent overflow on right edge (deployed envs may have different box models)
                            if (left + tooltipW + 32 > w) {
                                left = w - tooltipW - 32;
                            }
                            if (left < 0) left = 0;
                    
                            this.tooltipStyle = { left: left + 'px', top: top + 'px', transform: 'translateY(0)' };
                            this.tooltipPlacement = 'left';
                        },
                        clearActive() {
                            // small debounce so that entering the tooltip keeps it open
                            setTimeout(() => {
                                if (!this.tooltipHovered) {
                                    this.activeTooltip = null;
                                    this.activeFact = '';
                                    this.tooltipStyle = {};
                                    this.tooltipPlacement = null;
                                }
                            }, 120);
                        }
                    }">
                        <div class="rounded-lg overflow-hidden bg-[var(--color-muted)] relative inline-block w-full tooltip-image-container"
                            x-ref="imageContainer">
                            <img src="{{ $pet->image ? $pet->image_url : '/placeholder.svg?height=600&width=600' }}"
                                alt="{{ $pet->name }}" class="block w-full h-auto object-contain"
                                style="z-index: 10; display:block;" @mouseenter="showAllHotspots = true"
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
                                        [
                                            'feature' => 'ears',
                                            'position_x' => 50,
                                            'position_y' => 15,
                                            'width' => 64,
                                            'height' => 40,
                                        ],
                                        [
                                            'feature' => 'eyes',
                                            'position_x' => 50,
                                            'position_y' => 30,
                                            'width' => 64,
                                            'height' => 32,
                                        ],
                                        [
                                            'feature' => 'tail',
                                            'position_x' => 85,
                                            'position_y' => 70,
                                            'width' => 40,
                                            'height' => 48,
                                        ],
                                        [
                                            'feature' => 'paws',
                                            'position_x' => 30,
                                            'position_y' => 85,
                                            'width' => 32,
                                            'height' => 32,
                                        ],
                                    ];

                                    $hotspots = $pet->hotspots ?? $defaultHotspots;
                                    $funFacts = $pet->fun_facts ?? [];
                                @endphp

                                @foreach ($hotspots as $hotspot)
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
                                                'ears' => [
                                                    'default' =>
                                                        'Cats use their ears to detect faint sounds and orient themselves.',
                                                ],
                                                'eyes' => [
                                                    'default' =>
                                                        'Cats have excellent night vision thanks to a reflective layer behind the retina.',
                                                ],
                                                'tail' => [
                                                    'default' => 'A cat\'s tail helps with balance and communication.',
                                                ],
                                                'paws' => [
                                                    'default' =>
                                                        'Cats have sensitive paw pads used for hunting and sensing terrain.',
                                                ],
                                                'nose' => ['default' => 'A cat\'s nose is highly sensitive to scent.'],
                                                'coat' => [
                                                    'default' => 'Coat patterns and density vary by breed and climate.',
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

                                    <div class="absolute hotspot-wrapper"
                                        style="top: {{ $hotspot['position_y'] }}%; left: {{ $hotspot['position_x'] }}%; transform: translate(-50%, -50%);"
                                        @mouseenter="setActive('{{ $feature }}', $el)" @mouseleave="clearActive()"
                                        @click="setActive('{{ $feature }}', $el)" data-fact="{{ e($fact) }}"
                                        data-x="{{ $hotspot['position_x'] }}" data-y="{{ $hotspot['position_y'] }}">
                                        <div class="cursor-pointer rounded-full border-2 tooltip-hotspot flex items-center justify-center backdrop-blur-sm text-pink-700 pulse-animation"
                                            style="width: {{ max(40, $hotspot['width'] ?? 40) }}px; height: {{ max(40, $hotspot['height'] ?? 40) }}px;">
                                            <span class="text-xs font-semibold select-none">{{ ucfirst($feature) }}</span>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Shared tooltip element inside the image container so it never leaves the bounds --}}
                                <div x-show="activeTooltip" x-cloak @mouseenter="tooltipHovered = true"
                                    @mouseleave="tooltipHovered = false; clearActive()"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0" :style="tooltipStyle"
                                    :class="{ 'tooltip-content': true, 'show': activeTooltip !== null }"
                                    class="absolute z-50 p-0 bg-white rounded-lg shadow-lg w-40 sm:w-72 text-sm pointer-events-auto">
                                    <div :class="tooltipPlacement ? 'tooltip-arrow ' + tooltipPlacement : 'tooltip-arrow left'"
                                        aria-hidden="true"></div>
                                    <div class="p-3 max-h-[200px] overflow-y-auto">
                                        <strong class="block mb-1 text-[--color-primary]"
                                            x-text="(activeTooltip ? ('{{ $pet->name }}\'s ' + activeTooltip) : '')"></strong>
                                        <p x-text="activeFact"></p>
                                    </div>
                                </div>

                                <div class="absolute bottom-2 left-2 bg-black/50 text-white text-xs px-2 py-1 rounded"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                    x-show="showAllHotspots || activeTooltip !== null">
                                    <span x-show="activeTooltip === null">Hover or tap the circles to learn about
                                        features</span>
                                    <span x-show="activeTooltip !== null">Showing: <span
                                            x-text="activeTooltip"></span></span>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="space-y-6 pt-4">
                        @php
                            $characteristics = [
                                'Friendliness' => $pet->friendliness,
                                
                                'Exercise Needs' => $pet->exerciseNeeds,
                                'Grooming' => $pet->grooming,
                            ];

                            $characteristicDescriptions = [
                                'Friendliness' =>
                                    'How sociable the breed is with people and other animals (higher = very friendly).',
                                'Trainability' =>
                                    'How easy the breed is to train and respond to commands (higher = very trainable).',
                                'Exercise Needs' => 'Approximate daily activity needs (higher = needs more exercise).',
                                'Grooming' =>
                                    'Amount of grooming & coat maintenance required (higher = more grooming).',
                            ];
                        @endphp

                        @foreach ($characteristics as $label => $value)
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
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $value)
                                                <svg class="h-5 w-5 text-yellow-400 mr-1" viewBox="0 0 20 20"
                                                    fill="currentColor" aria-hidden="true">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.13 3.478a1 1 0 00.95.69h3.654c.969 0 1.371 1.24.588 1.81l-2.958 2.15a1 1 0 00-.364 1.118l1.13 3.478c.3.921-.755 1.688-1.54 1.118l-2.958-2.15a1 1 0 00-1.176 0l-2.958 2.15c-.784.57-1.838-.197-1.539-1.118l1.13-3.478a1 1 0 00-.364-1.118L2.38 8.905c-.783-.57-.38-1.81.588-1.81h3.654a1 1 0 00.95-.69l1.13-3.478z" />
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 text-gray-300 mr-1" viewBox="0 0 20 20"
                                                    fill="currentColor" aria-hidden="true">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.13 3.478a1 1 0 00.95.69h3.654c.969 0 1.371 1.24.588 1.81l-2.958 2.15a1 1 0 00-.364 1.118l1.13 3.478c.3.921-.755 1.688-1.54 1.118l-2.958-2.15a1 1 0 00-1.176 0l-2.958 2.15c-.784.57-1.838-.197-1.539-1.118l1.13-3.478a1 1 0 00-.364-1.118L2.38 8.905c-.783-.57-.38-1.81.588-1.81h3.654a1 1 0 00.95-.69l1.13-3.478z" />
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                </div>

                                {{-- Accessible textual fallback for screen readers --}}
                                <div class="sr-only">{{ $label }}: {{ $value }} out of 5.</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Right: Tabs & Details --}}
                <div class="space-y-6">
                    <div>
                        <h1 class="text-3xl font-bold">{{ $pet->name }}</h1>
                        <p class="text-[--color-muted-foreground]">
                            {{ $pet->price_range ?? ($pet->temperament ?? 'Price range not available') }}</p>

                        <div class="mt-3 flex items-center gap-3">
                            <a href="{{ route('cats') }}"
                                class="inline-flex items-center px-4 py-2 bg-[var(--color-primary)] text-white text-sm font-medium rounded-md transition
              hover:scale-105 hover:bg-pink-500 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-pink-300 active:scale-95"
                                style="will-change: transform, box-shadow;">
                                ← Back to Cat Breeds
                            </a>
                            @if ($pet->gif_url)
                                <button @click="showGifModal = true"
                                    class="inline-flex items-center px-4 py-2 bg-[var(--color-primary)] text-white text-sm font-medium rounded-md transition
                   hover:scale-105 hover:bg-yellow-400 hover:text-pink-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-yellow-300 active:scale-95"
                                    style="will-change: transform, box-shadow;">
                                    🎬 View Fun GIF
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Overview --}}
                    <div class="space-y-4 pt-4">
                        <div class="story-description">
                            <p id="petDescription" class="prose max-w-none">{{ $pet->description }}</p>
                            <div class="mt-3">
                                <button id="ttsPlayBtn" type="button"
                                    class="inline-flex items-center px-4 py-2 bg-[var(--color-primary)] text-white rounded-md"
                                    aria-pressed="false" aria-label="Play description">
                                    🔊 Play
                                </button>
                                <button id="ttsStopBtn" type="button"
                                    class="items-center px-3 py-2 ml-2 border rounded-md hidden" aria-label="Stop speech">
                                    ⏹ Stop
                                </button>
                            </div>
                        </div>
                        @if ($pet->colors)
                            @php
                                $colorImagesRaw = $pet->color_images;
                                $colorImageMap = [];

                                if (!empty($colorImagesRaw)) {
                                    $isAssoc = array_values($colorImagesRaw) !== $colorImagesRaw;
                                    if ($isAssoc) {
                                        $colorImageMap = $colorImagesRaw;
                                    } else {
                                        foreach ($colorImagesRaw as $item) {
                                            if (is_array($item) && isset($item['name'])) {
                                                $name = $item['name'];
                                                $img = $item['image'] ?? null;
                                                if ($img) {
                                                    $colorImageMap[$name] = $img;
                                                }
                                            }
                                        }
                                    }
                                }

                                $tagColors = is_array($pet->colors) ? $pet->colors : [];
                                $imageColors = array_keys($colorImageMap ?: []);
                                $displayColors = array_values(array_unique(array_merge($tagColors, $imageColors)));
                            @endphp

                            <div x-data="{
                                colorPreviewOpen: false,
                                colorPreviewImage: '',
                                previewStyle: {},
                                showPreview(url, ev) {
                                    if (!url) return;
                                    this.colorPreviewImage = url;
                                    this.colorPreviewOpen = true;
                                    // position above the target element (keep preview size fixed)
                                    const rect = ev.target.getBoundingClientRect();
                                    const previewHeight = 160; /* matches .h-40 */
                                    const width = 280; /* keep the beautiful size */
                                    let left = rect.left + window.scrollX;
                                    // ensure preview doesn't run off-screen to the right
                                    const maxLeft = window.innerWidth - width - 12;
                                    if (left > maxLeft) left = Math.max(12, maxLeft);
                                    const top = rect.top + window.scrollY - previewHeight - 8;
                                    this.previewStyle = {
                                        left: left + 'px',
                                        top: top + 'px',
                                        width: width + 'px'
                                    };
                                },
                                hidePreview() {
                                    this.colorPreviewOpen = false;
                                    this.colorPreviewImage = '';
                                    this.previewStyle = {};
                                }
                            }">
                                <h3 class="font-medium mb-2">Common Colors</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($displayColors as $color)
                                        @php
                                            $colorKey = $color;
                                            $colorImageUrl = null;
                                            if (!empty($colorImageMap)) {
                                                foreach ($colorImageMap as $k => $v) {
                                                    if (strtolower($k) === strtolower($colorKey)) {
                                                        $colorImageUrl = $v;
                                                        break;
                                                    }
                                                }
                                            }

                                            if ($colorImageUrl && !preg_match('/^https?:\/\//', $colorImageUrl)) {
                                                try {
                                                    $colorImageUrl = \Illuminate\Support\Facades\Storage::url(
                                                        $colorImageUrl,
                                                    );
                                                } catch (\Throwable $e) {
                                                    // leave as-is
                                                }
                                            }

                                            $finalImage = $colorImageUrl ?: $pet->image_url;
                                        @endphp

                                        <div @mouseenter="showPreview('{{ $finalImage ? e($finalImage) : '' }}', $event)"
                                            @mouseleave="hidePreview()"
                                            class="px-3 py-1 rounded-full text-sm border shadow-sm"
                                            style="background-color: color-mix(in oklab, var(--color-secondary) 12%, white); color: color-mix(in oklab, var(--color-secondary) 50%, black);">
                                            {{ $color }}
                                        </div>
                                    @endforeach
                                </div>


                                <!-- Hover preview card -->
                                <div x-show="colorPreviewOpen" x-cloak x-transition.opacity.scale.origin.top.left
                                    :style="previewStyle" class="fixed z-50 pointer-events-none">
                                    <div class="bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-100 animate-fade-in"
                                        style="width: 280px;">
                                        <div class="p-2">
                                            <img :src="colorPreviewImage" alt="Color preview"
                                                class="w-full h-40 object-cover rounded-md">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Common Diet block --}}
                        @php
                            // Get the URL-mapped diet images using the accessor
                            $dietImageUrls = $pet->diet_image_urls; // This returns ['name' => 'url'] mapping

                            // Merge names: tags (if any) + diet_images keys
                            $dietNames = [];
                            if (!empty($pet->diets) && is_array($pet->diets)) {
                                $dietNames = array_merge($dietNames, $pet->diets);
                            }
                            if (!empty($dietImageUrls) && is_array($dietImageUrls)) {
                                $dietNames = array_merge($dietNames, array_keys($dietImageUrls));
                            }
                            $dietNames = array_values(array_unique(array_filter($dietNames)));
                        @endphp

                        @if (!empty($dietNames))
                            <div x-data="{
                                dietPreviewOpen: false,
                                dietPreviewImage: '',
                                dietPreviewName: '',
                                dietPreviewStyle: {},
                                showDietPreview(url, name, ev) {
                                    if (!url) return;
                                    this.dietPreviewImage = url;
                                    this.dietPreviewName = name;
                                    this.dietPreviewOpen = true;
                                    // position above the target element (keep preview size fixed)
                                    const rect = ev.target.getBoundingClientRect();
                                    const previewHeight = 200; /* 160px image + 40px label */
                                    const width = 280; /* keep the beautiful size */
                                    let left = rect.left + window.scrollX;
                                    // ensure preview doesn't run off-screen to the right
                                    const maxLeft = window.innerWidth - width - 12;
                                    if (left > maxLeft) left = Math.max(12, maxLeft);
                                    const top = rect.top + window.scrollY - previewHeight - 8;
                                    this.dietPreviewStyle = {
                                        left: left + 'px',
                                        top: top + 'px',
                                        width: width + 'px'
                                    };
                                },
                                hideDietPreview() {
                                    this.dietPreviewOpen = false;
                                    this.dietPreviewImage = '';
                                    this.dietPreviewName = '';
                                    this.dietPreviewStyle = {};
                                }
                            }" class="mt-4">
                                <h3 class="font-medium mb-2">Common Diet</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($dietNames as $name)
                                        @php
                                            // Use the URL accessor which already handles conversion
                                            $dietImageUrl = $dietImageUrls[$name] ?? null;
                                            $finalDietImage = $dietImageUrl ?: '/placeholder.svg?height=160&width=280';
                                        @endphp

                                        <div @mouseenter="showDietPreview('{{ e($finalDietImage) }}', '{{ e(ucfirst($name)) }}', $event)"
                                            @mouseleave="hideDietPreview()"
                                            class="px-3 py-1 rounded-full text-sm border shadow-sm cursor-default"
                                            style="background-color: color-mix(in oklab, var(--color-secondary) 12%, white); color: color-mix(in oklab, var(--color-secondary) 50%, black);">
                                            {{ ucfirst($name) }}
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Hover preview card for diet -->
                                <div x-show="dietPreviewOpen" x-cloak x-transition.opacity.scale.origin.top.left
                                    :style="dietPreviewStyle" class="fixed z-50 pointer-events-none">
                                    <div class="bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-100 animate-fade-in"
                                        style="width: 280px;">
                                        <div class="p-2">
                                            <img :src="dietPreviewImage" alt="Diet preview"
                                                class="w-full h-40 object-cover rounded-md">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif


                    </div>
                </div>

                {{-- Image Gallery Carousel --}}
                @if (!empty($pet->gallery) && count($pet->gallery) > 0)
                    <div class="mt-12">
                        <h2 class="text-2xl font-bold mb-6">{{ $pet->name }} Gallery</h2>

                        <div class="relative gallery-carousel">
                            {{-- Previous Button --}}
                            <button @click="prevSlide()" :disabled="currentSlide === 0"
                                :class="{ 'opacity-50 cursor-not-allowed': currentSlide === 0 }" class="carousel-btn prev"
                                aria-label="Previous images">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>

                            {{-- Gallery Track --}}
                            <div class="overflow-hidden rounded-lg">
                                <div class="gallery-track"
                                    :style="{ transform: `translateX(-${currentSlide * (100 / visibleSlides)}%)` }">
                                    @foreach ($pet->gallery as $index => $galleryItem)
                                        @php
                                            // Gallery items may be stored as arrays (['url' => 'path'])
                                            // or as plain strings ("gallery/xxx.jpg" or full URLs).
                                            $raw = is_array($galleryItem) ? $galleryItem['url'] ?? null : $galleryItem;
                                            if (empty($raw)) {
                                                $imgSrc = '/placeholder.svg?height=400&width=400';
                                            } else {
                                                // If it's already a full URL, use it. Otherwise, resolve via Storage.
    if (preg_match('/^https?:\/\//i', $raw)) {
                                                    $imgSrc = $raw;
                                                } else {
                                                    try {
                                                        $imgSrc = \Illuminate\Support\Facades\Storage::url($raw);
                                                    } catch (\Throwable $e) {
                                                        // Fallback to the raw value if Storage resolution fails
                                                        $imgSrc = $raw;
                                                    }
                                                }
                                            }
                                        @endphp

                                        <div class="gallery-slide px-2">
                                            <img src="{{ e($imgSrc) }}"
                                                alt="{{ $pet->name }} - Image {{ $index + 1 }}"
                                                class="block w-full h-auto object-cover rounded-lg" />
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Next Button --}}
                            <button @click="nextSlide()" :disabled="currentSlide >= maxSlideIndex"
                                :class="{ 'opacity-50 cursor-not-allowed': currentSlide >= maxSlideIndex }"
                                class="carousel-btn next" aria-label="Next images">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>

                        {{-- Carousel Dots --}}
                        <div class="carousel-dots">
                            @foreach ($pet->gallery as $index => $galleryItem)
                                <button @click="goToSlide({{ $index }})"
                                    :class="{ 'active': currentSlide === {{ $index }} }" class="carousel-dot"
                                    aria-label="Go to image {{ $index + 1 }}">
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- GIF Modal --}}
                @if ($pet->gif_url)
                    <div x-show="showGifModal" x-transition.opacity
                        class="fixed inset-0 z-50 bg-black/60 flex items-center justify-center px-4 sm:px-6 md:px-8"
                        @click.self="showGifModal = false" x-cloak>
                        <div
                            class="relative w-full mx-auto rounded-2xl shadow-2xl overflow-hidden bg-white animate-fade-in-up
                            max-w-[min(1100px,calc(100vw-96px))] max-h-[90vh]">
                            <!-- Decorative paws scattered throughout -->
                            <div class="paw-icon" style="top: 8%; left: 5%;">🐾</div>
                            <div class="paw-icon" style="top: 12%; right: 8%; transform: rotate(25deg);">🐾</div>
                            <div class="paw-icon" style="top: 25%; left: 3%; transform: rotate(-45deg);">🐾</div>
                            <div class="paw-icon" style="top: 35%; right: 12%; transform: rotate(10deg);">🐾</div>
                            <div class="paw-icon" style="top: 45%; left: 8%; transform: rotate(35deg);">🐾</div>
                            <div class="paw-icon" style="top: 55%; right: 5%; transform: rotate(-20deg);">🐾</div>
                            <div class="paw-icon"
                                style="top: 18%; left: 15%; transform: rotate(60deg); font-size: 1.5rem;">🐾</div>
                            <div class="paw-icon"
                                style="top: 42%; right: 18%; transform: rotate(-35deg); font-size: 1.5rem;">🐾</div>
                            <div class="paw-icon" style="bottom: 15%; left: 10%; transform: rotate(-45deg);">🐾</div>
                            <div class="paw-icon" style="bottom: 25%; right: 15%; transform: rotate(15deg);">🐾</div>
                            <div class="paw-icon" style="bottom: 35%; left: 5%; transform: rotate(45deg);">🐾</div>
                            <div class="paw-icon" style="bottom: 8%; right: 8%; transform: rotate(-10deg);">🐾</div>
                            <div class="paw-icon"
                                style="bottom: 45%; left: 12%; transform: rotate(20deg); font-size: 1.5rem;">🐾</div>
                            <div class="paw-icon"
                                style="bottom: 18%; right: 20%; transform: rotate(-55deg); font-size: 1.2rem;">🐾</div>
                            <div class="paw-icon"
                                style="top: 65%; left: 4%; transform: rotate(50deg); font-size: 1.8rem;">🐾</div>
                            <div class="paw-icon"
                                style="top: 75%; right: 10%; transform: rotate(-15deg); font-size: 1.3rem;">🐾</div>
                            <div class="paw-icon"
                                style="top: 5%; left: 25%; transform: rotate(30deg); font-size: 1.4rem;">🐾</div>
                            <div class="paw-icon"
                                style="top: 28%; right: 22%; transform: rotate(-40deg); font-size: 1.6rem;">🐾</div>
                            <div class="paw-icon"
                                style="bottom: 5%; left: 18%; transform: rotate(65deg); font-size: 1.7rem;">🐾</div>
                            <div class="paw-icon"
                                style="bottom: 52%; right: 6%; transform: rotate(-25deg); font-size: 1.1rem;">🐾</div>
                            <div class="paw-icon"
                                style="top: 82%; left: 22%; transform: rotate(15deg); font-size: 1.9rem;">🐾</div>
                            <div class="paw-icon"
                                style="top: 92%; right: 25%; transform: rotate(-50deg); font-size: 1.5rem;">🐾</div>
                            <div class="paw-icon"
                                style="top: 3%; right: 18%; transform: rotate(40deg); font-size: 1.3rem;">🐾</div>
                            <div class="paw-icon"
                                style="top: 20%; left: 9%; transform: rotate(-30deg); font-size: 1.6rem;">🐾</div>
                            <div class="paw-icon"
                                style="top: 32%; right: 4%; transform: rotate(55deg); font-size: 1.2rem;">🐾</div>
                            <div class="paw-icon"
                                style="top: 48%; left: 2%; transform: rotate(-60deg); font-size: 1.4rem;">🐾</div>
                            <div class="paw-icon"
                                style="top: 60%; right: 14%; transform: rotate(20deg); font-size: 1.7rem;">🐾</div>
                            <div class="paw-icon"
                                style="top: 70%; left: 18%; transform: rotate(-25deg); font-size: 1.1rem;">🐾</div>
                            <div class="paw-icon"
                                style="top: 88%; right: 12%; transform: rotate(45deg); font-size: 1.5rem;">🐾</div>
                            <div class="paw-icon"
                                style="bottom: 3%; left: 8%; transform: rotate(-40deg); font-size: 1.8rem;">🐾</div>
                            <div class="paw-icon"
                                style="bottom: 12%; right: 22%; transform: rotate(30deg); font-size: 1.3rem;">🐾</div>
                            <div class="paw-icon"
                                style="bottom: 28%; left: 20%; transform: rotate(-50deg); font-size: 1.6rem;">🐾</div>
                            <div class="paw-icon"
                                style="bottom: 40%; right: 10%; transform: rotate(60deg); font-size: 1.2rem;">🐾</div>
                            <div class="paw-icon"
                                style="bottom: 58%; left: 7%; transform: rotate(-15deg); font-size: 1.4rem;">🐾</div>
                            <div class="paw-icon"
                                style="top: 14%; left: 28%; transform: rotate(35deg); font-size: 1.5rem;">🐾</div>
                            <div class="paw-icon"
                                style="top: 38%; right: 28%; transform: rotate(-45deg); font-size: 1.3rem;">🐾</div>
                            <div class="paw-icon"
                                style="top: 52%; left: 24%; transform: rotate(50deg); font-size: 1.7rem;">🐾</div>
                            <div class="paw-icon"
                                style="bottom: 22%; right: 2%; transform: rotate(-35deg); font-size: 1.1rem;">🐾</div>
                            <div class="paw-icon"
                                style="bottom: 48%; left: 16%; transform: rotate(25deg); font-size: 1.6rem;">🐾</div>
                            <div class="paw-icon"
                                style="bottom: 62%; right: 24%; transform: rotate(-20deg); font-size: 1.4rem;">🐾</div>

                            <button @click="showGifModal = false"
                                class="absolute top-3 right-3 z-30 inline-flex items-center justify-center h-10 w-10 rounded-full bg-white hover:bg-gray-100 text-gray-700 shadow-lg transition"
                                aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <div class="flex flex-col sm:flex-row gap-4 p-3 sm:p-5 h-full">
                                <div
                                    class="flex-1 flex items-center justify-center bg-gray-100 rounded-md p-2 overflow-auto">
                                    <img src="{{ $pet->gif_url }}" alt="GIF of {{ $pet->name }}"
                                        class="max-w-full w-auto max-h-[80vh] rounded-md object-contain" />
                                </div>

                                <div
                                    class="sm:w-64 flex flex-col justify-center gap-3 text-center sm:text-left bg-white/95 backdrop-blur-sm p-4 rounded-lg relative z-10 overflow-hidden">
                                    <!-- Decorative paws for text panel -->
                                    <div class="paw-icon" style="top: 5%; right: 10%; font-size: 1.5rem;">🐾</div>
                                    <div class="paw-icon"
                                        style="top: 15%; left: 8%; font-size: 1.2rem; transform: rotate(45deg);">🐾</div>
                                    <div class="paw-icon"
                                        style="top: 25%; right: 15%; font-size: 1rem; transform: rotate(-30deg);">🐾</div>
                                    <div class="paw-icon"
                                        style="top: 35%; left: 5%; font-size: 1.3rem; transform: rotate(60deg);">🐾</div>
                                    <div class="paw-icon"
                                        style="top: 45%; right: 8%; font-size: 1.4rem; transform: rotate(-15deg);">🐾</div>
                                    <div class="paw-icon"
                                        style="top: 55%; left: 12%; font-size: 1.1rem; transform: rotate(25deg);">🐾</div>
                                    <div class="paw-icon"
                                        style="top: 65%; right: 18%; font-size: 1.6rem; transform: rotate(-45deg);">🐾
                                    </div>
                                    <div class="paw-icon"
                                        style="top: 75%; left: 6%; font-size: 1.2rem; transform: rotate(35deg);">🐾</div>
                                    <div class="paw-icon"
                                        style="bottom: 10%; left: 5%; transform: rotate(-25deg); font-size: 1.2rem;">🐾
                                    </div>
                                    <div class="paw-icon"
                                        style="bottom: 20%; right: 12%; transform: rotate(50deg); font-size: 1.5rem;">🐾
                                    </div>
                                    <div class="paw-icon"
                                        style="bottom: 30%; left: 15%; transform: rotate(-10deg); font-size: 1.3rem;">🐾
                                    </div>
                                    <div class="paw-icon"
                                        style="bottom: 5%; right: 5%; transform: rotate(40deg); font-size: 1rem;">🐾</div>
                                    <div class="paw-icon"
                                        style="top: 10%; left: 20%; transform: rotate(-55deg); font-size: 1.4rem;">🐾</div>
                                    <div class="paw-icon"
                                        style="bottom: 15%; left: 22%; transform: rotate(20deg); font-size: 1.7rem;">🐾
                                    </div>

                                    <h2 class="text-lg font-semibold text-gray-900 relative z-20">🎬 {{ $pet->name }}
                                        in Action</h2>
                                    <p class="text-sm text-gray-600 relative z-20">A short cute clip of
                                        {{ $pet->name }}. Click outside or the close button to dismiss.</p>

                                    <div class="flex justify-center sm:justify-start gap-2 mt-2 relative z-20">
                                        <button @click="showGifModal = false"
                                            class="px-3 py-2 rounded-md bg-[var(--color-primary)] text-white text-sm hover:opacity-90 transition">Close</button>
                                        @if ($pet->image_url)
                                            <a href="{{ $pet->image_url }}" target="_blank"
                                                class="px-3 py-2 rounded-md border border-gray-300 text-sm hover:bg-gray-50 transition">Open
                                                image</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const playBtn = document.getElementById('ttsPlayBtn');
            const stopBtn = document.getElementById('ttsStopBtn');
            const textEl = document.getElementById('petDescription');

            if (!('speechSynthesis' in window)) {
                if (playBtn) {
                    playBtn.disabled = true;
                    playBtn.title = 'Text-to-speech not supported';
                }
                return;
            }

            let utterance = null;

            function speak() {
                if (!textEl || !textEl.innerText.trim()) return;
                window.speechSynthesis.cancel();
                utterance = new SpeechSynthesisUtterance(textEl.innerText.trim());
                utterance.lang = 'en-US';
                utterance.rate = 1;
                utterance.pitch = 1;
                utterance.onend = () => {
                    playBtn.textContent = '🔊 Play';
                    playBtn.setAttribute('aria-pressed', 'false');
                    stopBtn.classList.add('hidden');
                };
                window.speechSynthesis.speak(utterance);
                playBtn.textContent = '⏸ Pause';
                playBtn.setAttribute('aria-pressed', 'true');
                stopBtn.classList.remove('hidden');
            }

            function stop() {
                window.speechSynthesis.cancel();
                playBtn.textContent = '🔊 Play';
                playBtn.setAttribute('aria-pressed', 'false');
                stopBtn.classList.add('hidden');
            }

            playBtn.addEventListener('click', function() {
                if (window.speechSynthesis.speaking) {
                    if (window.speechSynthesis.paused) {
                        window.speechSynthesis.resume();
                        playBtn.textContent = '⏸ Pause';
                        playBtn.setAttribute('aria-pressed', 'true');
                    } else {
                        window.speechSynthesis.pause();
                        playBtn.textContent = '▶️ Resume';
                        playBtn.setAttribute('aria-pressed', 'false');
                    }
                } else {
                    speak();
                }
            });

            stopBtn.addEventListener('click', stop);
            window.addEventListener('beforeunload', stop);
        });
    </script>
@endsection
