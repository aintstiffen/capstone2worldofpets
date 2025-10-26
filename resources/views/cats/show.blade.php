    @extends('layouts.app')

    @push('styles')
        <style>
            :root {
                --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                --gradient-accent: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
                --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
                --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.12);
                --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.16);
            }

            /* Hero Section */
            .hero-gradient {
                background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
                position: relative;
                overflow: hidden;
            }

            .hero-gradient::before {
                content: '';
                position: absolute;
                inset: 0;
                background-image: 
                    radial-gradient(circle at 20% 50%, rgba(102, 126, 234, 0.08) 1px, transparent 1px),
                    radial-gradient(circle at 80% 50%, rgba(118, 75, 162, 0.08) 1px, transparent 1px);
                background-size: 40px 40px;
                animation: float-pattern 20s ease-in-out infinite;
            }

            @keyframes float-pattern {
                0%, 100% { transform: translate(0, 0); }
                50% { transform: translate(20px, 20px); }
            }

            /* Glass Card Effect */
            .glass-card {
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.5);
                box-shadow: var(--shadow-md);
                border-radius: 24px;
                transition: all 0.3s ease;
            }

            .glass-card:hover {
                box-shadow: var(--shadow-lg);
                transform: translateY(-4px);
            }

            /* Main Image Container with Enhanced Styling */
            .main-image-wrapper {
                position: relative;
                border-radius: 32px;
                overflow: hidden;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
                background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
                max-height: 450px;
                max-width: 700px;
                margin: 0 auto;
            }

            .main-image-wrapper::before {
                content: '';
                position: absolute;
                inset: -2px;
                background: var(--gradient-primary);
                border-radius: 32px;
                z-index: -1;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .main-image-wrapper:hover::before {
                opacity: 0.3;
            }

            .main-image-wrapper img {
                transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
                width: 100%;
                height: 100%;
                object-fit: contain;
            }

            .main-image-wrapper:hover img {
                transform: scale(1.03);
            }

            /* Interactive Hotspots - Enhanced */
            .hotspot-marker {
                position: relative;
                cursor: pointer;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .hotspot-marker::before {
                content: '';
                position: absolute;
                inset: -8px;
                border-radius: 50%;
                background: rgba(102, 126, 234, 0.2);
                animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            @keyframes pulse-ring {
                0%, 100% {
                    transform: scale(1);
                    opacity: 0.5;
                }
                50% {
                    transform: scale(1.3);
                    opacity: 0;
                }
            }

            .hotspot-marker:hover {
                transform: scale(1.15);
            }

            /* Tooltip Enhancement */
            .tooltip-enhanced {
                background: white;
                border-radius: 20px;
                padding: 20px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
                border: 2px solid rgba(102, 126, 234, 0.1);
                max-width: 350px;
            }

            /* Stats Card */
            .stat-card {
                background: white;
                border-radius: 20px;
                padding: 24px;
                box-shadow: var(--shadow-sm);
                transition: all 0.3s ease;
                border: 2px solid transparent;
            }

            .stat-card:hover {
                border-color: var(--color-primary);
                box-shadow: var(--shadow-md);
                transform: translateY(-2px);
            }

            /* Progress Bar Enhanced */
            .progress-container {
                height: 12px;
                background: linear-gradient(90deg, #f5f7fa 0%, #e8ecf1 100%);
                border-radius: 6px;
                overflow: hidden;
                position: relative;
            }

            .progress-fill {
                height: 100%;
                background: var(--gradient-primary);
                border-radius: 6px;
                transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                overflow: hidden;
            }

            .progress-fill::after {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
                animation: shimmer 2s infinite;
            }

            @keyframes shimmer {
                0% { transform: translateX(-100%); }
                100% { transform: translateX(100%); }
            }

            /* Info Pills Enhanced */
            .info-pill {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                padding: 12px 20px;
                border-radius: 24px;
                font-size: 14px;
                font-weight: 600;
                background: white;
                border: 2px solid rgba(102, 126, 234, 0.2);
                box-shadow: var(--shadow-sm);
                transition: all 0.3s ease;
                cursor: default;
            }

            .info-pill:hover {
                transform: translateY(-3px);
                box-shadow: var(--shadow-md);
                border-color: var(--color-primary);
            }

            .info-pill .icon {
                font-size: 20px;
                filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
            }

            /* Section Headers */
            .section-title {
                position: relative;
                padding-left: 20px;
                font-size: 28px;
                font-weight: 700;
                margin-bottom: 24px;
            }

            .section-title::before {
                content: '';
                position: absolute;
                left: 0;
                top: 0;
                bottom: 0;
                width: 6px;
                background: var(--gradient-primary);
                border-radius: 3px;
            }

            /* Gallery Grid Enhanced */
            .gallery-grid-enhanced {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 20px;
            }

            .gallery-item-enhanced {
                position: relative;
                aspect-ratio: 4/3;
                border-radius: 20px;
                overflow: hidden;
                cursor: pointer;
                box-shadow: var(--shadow-sm);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                max-height: 300px;
            }

            .gallery-item-enhanced:hover {
                transform: scale(1.05) translateY(-8px);
                box-shadow: var(--shadow-lg);
            }

            .gallery-item-enhanced img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s ease;
            }

            .gallery-item-enhanced:hover img {
                transform: scale(1.1);
            }

            .gallery-item-enhanced::after {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.4) 100%);
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .gallery-item-enhanced:hover::after {
                opacity: 1;
            }

            /* Color/Diet Tags Enhanced */
            .tag-enhanced {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 18px;
                border-radius: 20px;
                font-size: 14px;
                font-weight: 500;
                background: white;
                border: 2px solid rgba(102, 126, 234, 0.15);
                box-shadow: var(--shadow-sm);
                transition: all 0.3s ease;
                cursor: pointer;
            }

            .tag-enhanced:hover {
                transform: translateY(-3px) scale(1.05);
                box-shadow: var(--shadow-md);
                border-color: var(--color-primary);
            }

            /* Action Buttons Enhanced */
            .btn-primary-enhanced {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                padding: 14px 28px;
                border-radius: 16px;
                font-weight: 600;
                background: var(--gradient-primary);
                color: white;
                border: none;
                box-shadow: var(--shadow-sm);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                cursor: pointer;
            }

            .btn-primary-enhanced:hover {
                transform: translateY(-3px) scale(1.02);
                box-shadow: var(--shadow-lg);
            }

            .btn-primary-enhanced:active {
                transform: translateY(-1px) scale(0.98);
            }

            .btn-secondary-enhanced {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                padding: 14px 28px;
                border-radius: 16px;
                font-weight: 600;
                background: white;
                color: #667eea;
                border: 2px solid #667eea;
                box-shadow: var(--shadow-sm);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                cursor: pointer;
            }

            .btn-secondary-enhanced:hover {
                background: #667eea;
                color: white;
                transform: translateY(-3px) scale(1.02);
                box-shadow: var(--shadow-lg);
            }

            /* TTS Controls Enhanced */
            .tts-controls {
                display: flex;
                gap: 12px;
                margin-top: 20px;
            }

            /* Facts Grid */
            .facts-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 16px;
                margin-top: 24px;
            }

            .fact-card {
                background: white;
                border-radius: 16px;
                padding: 20px;
                box-shadow: var(--shadow-sm);
                border: 2px solid transparent;
                transition: all 0.3s ease;
            }

            .fact-card:hover {
                border-color: var(--color-primary);
                transform: translateY(-4px);
                box-shadow: var(--shadow-md);
            }

            .fact-label {
                font-size: 12px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: #667eea;
                margin-bottom: 8px;
            }

            .fact-value {
                font-size: 18px;
                font-weight: 700;
                color: #1a202c;
            }

            /* Preview Cards - FIXED */
            .preview-card-enhanced {
                position: fixed;
                z-index: 99999 !important;
                width: 320px;
                max-width: calc(100vw - 32px);
                background: white;
                border-radius: 20px;
                box-shadow: 0 25px 70px rgba(0, 0, 0, 0.4);
                overflow: hidden;
                pointer-events: auto;
                border: 3px solid rgba(102, 126, 234, 0.3);
            }

            .preview-card-enhanced img {
                width: 100%;
                height: auto;
                max-height: 240px;
                object-fit: cover;
                display: block;
            }

            .preview-card-enhanced .content {
                padding: 16px;
            }

            .preview-card-enhanced .title {
                font-weight: 700;
                font-size: 16px;
                color: #1a202c;
                margin-bottom: 4px;
            }

            /* Responsive Adjustments */
            @media (max-width: 768px) {
                .section-title {
                    font-size: 24px;
                }

                .glass-card {
                    border-radius: 16px;
                }

                .main-image-wrapper {
                    border-radius: 20px;
                }

                .facts-grid {
                    grid-template-columns: 1fr;
                }
            }

            /* Animation for page load */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in-up {
                animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            }

            .stagger-1 { animation-delay: 0.1s; opacity: 0; }
            .stagger-2 { animation-delay: 0.2s; opacity: 0; }
            .stagger-3 { animation-delay: 0.3s; opacity: 0; }
            .stagger-4 { animation-delay: 0.4s; opacity: 0; }
        </style>
    @endpush

    @section('content')
        <main class="flex-1" x-data="{
            showGifModal: false,
            lightboxOpen: false,
            lightboxImage: '',
            currentSlide: 0,
            totalSlides: {{ count($pet->gallery ?? []) }},
            get visibleSlides() {
                if (window.innerWidth >= 1024) return 4;
                if (window.innerWidth >= 768) return 3;
                if (window.innerWidth >= 640) return 2;
                return 1;
            },
            get maxSlideIndex() {
                return Math.max(0, this.totalSlides - this.visibleSlides);
            },
            nextSlide() {
                if (this.currentSlide < this.maxSlideIndex) this.currentSlide++;
            },
            prevSlide() {
                if (this.currentSlide > 0) this.currentSlide--;
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
        }">
            <!-- Hero Section -->
            <div class="hero-gradient py-8 md:py-12">
                <div class="container mx-auto px-4 md:px-6">
                    <!-- Breadcrumb -->
                    <nav class="flex items-center gap-2 text-sm mb-8 animate-fade-in-up">
                        <a href="{{ route('cats') }}" class="text-gray-600 hover:text-[var(--color-primary)] transition flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Cat Breeds
                        </a>
                        <span class="text-gray-400">/</span>
                        <span class="text-gray-900 font-semibold">{{ $pet->name }}</span>
                    </nav>

                    <!-- Hero Content -->
                    <div class="grid lg:grid-cols-5 gap-8 lg:gap-12">
                        <!-- Left Column: Image & Interactive Features (3/5) -->
                        <div class="lg:col-span-3 space-y-6">
                            <!-- Main Image with Hotspots -->
                            <div class="animate-fade-in-up stagger-1" x-data="{
                                activeTooltip: null,
                                activeFact: '',
                                tooltipStyle: {},
                                tooltipHovered: false,
                                setActive(feature, hotspotEl) {
                                    this.activeTooltip = feature;
                                    this.activeFact = hotspotEl.dataset.fact || '';
                                    
                                    const container = this.$refs.imageContainer;
                                    if (!container) return;
                                    const containerRect = container.getBoundingClientRect();
                                    const elRect = hotspotEl.getBoundingClientRect();
                                    
                                    const cx = (elRect.left - containerRect.left) + (elRect.width / 2);
                                    const cy = (elRect.top - containerRect.top) + (elRect.height / 2);
                                    const w = containerRect.width;
                                    const h = containerRect.height;
                                    
                                    const padding = 12;
                                    let tooltipW = window.innerWidth >= 1024 ? 350 : 320;
                                    let left = w > (tooltipW + padding * 2) ? w - tooltipW - padding - 32 : Math.max(padding, (w - tooltipW) / 2);
                                    let tooltipH = 200;
                                    let top = Math.max(padding, Math.min(cy, h - tooltipH - padding));
                                    
                                    if (left + tooltipW + 32 > w) left = w - tooltipW - 32;
                                    if (left < 0) left = 0;
                                    
                                    this.tooltipStyle = { left: left + 'px', top: top + 'px' };
                                },
                                clearActive() {
                                    setTimeout(() => {
                                        if (!this.tooltipHovered) {
                                            this.activeTooltip = null;
                                            this.activeFact = '';
                                            this.tooltipStyle = {};
                                        }
                                    }, 120);
                                }
                            }">
                                <div class="main-image-wrapper relative" x-ref="imageContainer">
                                    <img src="{{ $pet->image ? $pet->image_url : '/placeholder.svg?height=600&width=600' }}"
                                        alt="{{ $pet->name }}"
                                        class="w-full h-auto"
                                        style="max-height: 450px; object-fit: contain;">

                                    <!-- Hotspots -->
                                    <div class="absolute inset-0" style="z-index: 15">
                                        @php
                                            $defaultHotspots = [
                                                ['feature' => 'ears', 'position_x' => 50, 'position_y' => 15, 'width' => 48, 'height' => 48],
                                                ['feature' => 'eyes', 'position_x' => 50, 'position_y' => 30, 'width' => 48, 'height' => 48],
                                                ['feature' => 'tail', 'position_x' => 85, 'position_y' => 70, 'width' => 48, 'height' => 48],
                                                ['feature' => 'paws', 'position_x' => 30, 'position_y' => 85, 'width' => 48, 'height' => 48],
                                            ];
                                            $hotspots = $pet->hotspots ?? $defaultHotspots;
                                            $funFacts = $pet->fun_facts ?? [];
                                            
                                            $defaultFacts = [
                                                'ears' => 'Cats use their ears to detect faint sounds and orient themselves.',
                                                'eyes' => 'Cats have excellent night vision thanks to a reflective layer behind the retina.',
                                                'tail' => 'A cat\'s tail helps with balance and communication.',
                                                'paws' => 'Cats have sensitive paw pads used for hunting and sensing terrain.',
                                                'nose' => 'A cat\'s nose is highly sensitive to scent.',
                                                'coat' => 'Coat patterns and density vary by breed and climate.',
                                            ];
                                        @endphp

                                        @foreach ($hotspots as $hotspot)
                                            @php
                                                $feature = $hotspot['feature'] ?? 'feature';
                                                $fact = null;
                                                foreach ($funFacts as $ff) {
                                                    if (isset($ff['feature']) && $ff['feature'] === $feature) {
                                                        $fact = $ff['fact'] ?? null;
                                                        break;
                                                    }
                                                }
                                                if (!$fact) $fact = $defaultFacts[$feature] ?? 'Interesting facts about this ' . $feature . '.';
                                            @endphp

                                            <div class="absolute"
                                                style="top: {{ $hotspot['position_y'] }}%; left: {{ $hotspot['position_x'] }}%; transform: translate(-50%, -50%);"
                                                @mouseenter="setActive('{{ $feature }}', $el)"
                                                @mouseleave="clearActive()"
                                                @click="setActive('{{ $feature }}', $el)"
                                                data-fact="{{ e($fact) }}">
                                                <div class="hotspot-marker cursor-pointer rounded-full flex items-center justify-center backdrop-blur-sm bg-white/90 border-2 border-[var(--color-primary)] shadow-lg"
                                                    style="width: {{ $hotspot['width'] ?? 48 }}px; height: {{ $hotspot['height'] ?? 48 }}px;">
                                                    <span class="text-xs font-bold text-[var(--color-primary)]">{{ ucfirst($feature) }}</span>
                                                </div>
                                            </div>
                                        @endforeach

                                        <!-- Tooltip -->
                                        <div x-show="activeTooltip" x-cloak
                                            @mouseenter="tooltipHovered = true"
                                            @mouseleave="tooltipHovered = false; clearActive()"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            :style="tooltipStyle"
                                            class="tooltip-enhanced absolute z-50 pointer-events-auto">
                                            <div class="font-bold text-lg text-[var(--color-primary)] mb-2" x-text="activeTooltip ? '{{ $pet->name }}\'s ' + activeTooltip : ''"></div>
                                            <p class="text-sm text-gray-700" x-text="activeFact"></p>
                                        </div>

                                        <!-- Hint -->
                                        <div class="absolute bottom-4 left-4 right-4 text-center">
                                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-black/70 text-white text-xs rounded-full backdrop-blur-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>Hover or tap circles to learn more</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    <!-- Right Column: Details (2/5) -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Title & Action Buttons -->
                            <div class="animate-fade-in-up stagger-2">
                                <h1 class="text-4xl md:text-5xl font-bold mb-3 bg-gradient-to-r from-[#667eea] to-[#764ba2] bg-clip-text text-transparent">
                                    {{ $pet->name }}
                                </h1>
        
                            </div>
                            <!-- Quick Facts Grid -->
                            <div class="facts-grid animate-fade-in-up stagger-2">
                                <div class="fact-card">
                                    <div class="fact-label">Average Weight</div>
                                    <div class="fact-value">{{ $pet->average_weight ?? 'N/A' }}</div>
                                </div>
                                <div class="fact-card">
                                    <div class="fact-label">Origin</div>
                                    <div class="fact-value">{{ $pet->origin ?? 'Unknown' }}</div>
                                </div>
                                <div class="fact-card">
                                    <div class="fact-label">Energy Level</div>
                                    <div class="fact-value">{{ $pet->energy_level ?? 'Moderate' }}</div>
                                </div>
                                @if($pet->life_span)
                                <div class="fact-card">
                                    <div class="fact-label">Life Span</div>
                                    <div class="fact-value">{{ $pet->life_span }} years</div>
                                </div>
                                @endif
                            </div>

                            <!-- Characteristics -->
                            <div class="glass-card p-6 animate-fade-in-up stagger-3">
                                <h3 class="section-title text-xl">Breed Characteristics</h3>
                                
                                @php
                                    $characteristics = [
                                        'Friendliness' => ['value' => $pet->friendliness, 'desc' => 'How sociable with people and other animals'],
                                        'Grooming' => ['value' => $pet->grooming, 'desc' => 'Amount of grooming & coat maintenance required'],
                                    ];
                                @endphp

                                <div class="space-y-6">
                                    @foreach ($characteristics as $label => $data)
                                        @php $value = (int) $data['value']; @endphp
                                        <div>
                                            <div class="flex justify-between items-start mb-3">
                                                <div>
                                                    <div class="font-semibold text-gray-900">{{ $label }}</div>
                                                    <div class="text-xs text-gray-600 mt-1">{{ $data['desc'] }}</div>
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <svg class="w-5 h-5 {{ $i <= $value ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.13 3.478a1 1 0 00.95.69h3.654c.969 0 1.371 1.24.588 1.81l-2.958 2.15a1 1 0 00-.364 1.118l1.13 3.478c.3.921-.755 1.688-1.54 1.118l-2.958-2.15a1 1 0 00-1.176 0l-2.958 2.15c-.784.57-1.838-.197-1.539-1.118l1.13-3.478a1 1 0 00-.364-1.118L2.38 8.905c-.783-.57-.38-1.81.588-1.81h3.654a1 1 0 00.95-.69l1.13-3.478z" />
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="progress-container">
                                                <div class="progress-fill" style="width: 0" data-width="{{ ($value / 5) * 100 }}"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        

                            <!-- Description with TTS -->
                            <div class="glass-card p-6 animate-fade-in-up stagger-3">
                                <h3 class="section-title text-xl">About {{ $pet->name }}</h3>
                                <p id="petDescription" class="text-gray-700 leading-relaxed mb-4">{{ $pet->description }}</p>
                                
                                <div class="tts-controls">
                                    <button id="ttsPlayBtn" type="button" class="btn-secondary-enhanced text-sm py-2" aria-pressed="false">
                                        🔊 Play
                                    </button>
                                    <button id="ttsStopBtn" type="button" class="btn-secondary-enhanced text-sm py-2 hidden" aria-label="Stop speech">
                                        ⏹ Stop
                                    </button>
                                </div>
                            </div>

                            <!-- Colors Section with FIXED Modal -->
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
                                                    if ($img) $colorImageMap[$name] = $img;
                                                }
                                            }
                                        }
                                    }
                                    $tagColors = is_array($pet->colors) ? $pet->colors : [];
                                    $imageColors = array_keys($colorImageMap ?: []);
                                    $displayColors = array_values(array_unique(array_merge($tagColors, $imageColors)));
                                @endphp

                                <div class="glass-card p-6 animate-fade-in-up stagger-4" x-data="{
                                    colorPreviewOpen: false,
                                    colorPreviewImage: '',
                                    previewStyle: {},
                                    previewTimeout: null,
                                        showPreview(url, ev) {
                                            if (!url) return;
                                            console.log('Showing color preview:', url);
                                            clearTimeout(this.previewTimeout);
                                            this.colorPreviewImage = url;
                                            this.colorPreviewOpen = true;
                                            // Prefer currentTarget (the element with the listener). Fall back to target.
                                            // Use viewport coordinates (getBoundingClientRect gives viewport coords)
                                            const rect = (ev && ev.currentTarget)
                                                ? ev.currentTarget.getBoundingClientRect()
                                                : (ev && ev.target)
                                                    ? ev.target.getBoundingClientRect()
                                                    : { left: 0, top: 0, width: 0, bottom: 0 };
                                            const previewHeight = 240;
                                            const width = 320;
                                            // For fixed preview, use rect values (viewport-based) without adding scroll offsets
                                            let left = rect.left - (width / 2) + (rect.width / 2);
                                            const maxLeft = window.innerWidth - width - 12;
                                            if (left > maxLeft) left = maxLeft;
                                            if (left < 12) left = 12;
                                            let top = rect.top - previewHeight - 12;
                                            if (top < 12) top = rect.bottom + 12;
                                            this.previewStyle = {
                                                left: left + 'px',
                                                top: top + 'px',
                                                width: width + 'px',
                                                display: 'block',
                                                zIndex: '99999'
                                            };
                                        },
                                    hidePreview() {
                                        this.previewTimeout = setTimeout(() => {
                                            console.log('Hiding color preview');
                                            this.colorPreviewOpen = false;
                                            this.colorPreviewImage = '';
                                            this.previewStyle = {};
                                        }, 150);
                                    }
                                }">
                                    <h3 class="section-title text-xl">Available Colors</h3>
                    <div class="flex flex-wrap gap-3">
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
                                                        $colorImageUrl = \Illuminate\Support\Facades\Storage::url($colorImageUrl);
                                                    } catch (\Throwable $e) {}
                                                }
                                                $finalImage = $colorImageUrl ?: $pet->image_url;
                                            @endphp

                                            <div class="tag-enhanced preview-trigger" data-preview="{{ $finalImage ? e($finalImage) : '' }}" role="button" tabindex="0">
                                                {{ $color }}
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                </div>
                            @endif

                            <!-- Diet Section with FIXED Modal -->
                            @php
                                $dietImageUrls = $pet->diet_image_urls;
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
                                <div class="glass-card p-6 animate-fade-in-up stagger-4" style="position: relative; z-index: 1;" x-data="{
                                    dietPreviewOpen: false,
                                    dietPreviewImage: '',
                                    dietPreviewName: '',
                                    dietPreviewStyle: {},
                                    dietTimeout: null,
                                        showDietPreview(url, name, ev) {
                                            if (!url) return;
                                            clearTimeout(this.dietTimeout);
                                            this.dietPreviewImage = url;
                                            this.dietPreviewName = name;
                                            this.dietPreviewOpen = true;
                                            const rect = ev.currentTarget.getBoundingClientRect();
                                            const previewWidth = 320;
                                            const previewHeight = 240;
                                            let left = rect.left + (rect.width / 2) - (previewWidth / 2);
                                            let top = rect.top - previewHeight - 16;
                                            
                                            // Keep within viewport bounds
                                            const viewportWidth = window.innerWidth;
                                            const viewportHeight = window.innerHeight;
                                            
                                            if (left + previewWidth > viewportWidth - 16) {
                                                left = viewportWidth - previewWidth - 16;
                                            }
                                            if (left < 16) {
                                                left = 16;
                                            }
                                            
                                            // If not enough space above, show below
                                            if (top < 16) {
                                                top = rect.bottom + 16;
                                            }
                                            
                                            // If still going off bottom, show above tag centered
                                            if (top + previewHeight > viewportHeight - 16) {
                                                top = rect.top - previewHeight - 16;
                                            }
                                            
                                            this.dietPreviewStyle = {
                                                left: left + 'px',
                                                top: top + 'px'
                                            };
                                        },
                                    hideDietPreview() {
                                        this.dietTimeout = setTimeout(() => {
                                            this.dietPreviewOpen = false;
                                            this.dietPreviewImage = '';
                                            this.dietPreviewName = '';
                                            this.dietPreviewStyle = {};
                                        }, 150);
                                    }
                                }">
                                    <h3 class="section-title text-xl">Recommended Diet</h3>
                                    <div class="flex flex-wrap gap-3">
                                        @foreach ($dietNames as $name)
                                            @php
                                                $dietImageUrl = $dietImageUrls[$name] ?? null;
                                                $finalDietImage = $dietImageUrl ?: '/placeholder.svg?height=160&width=280';
                                            @endphp

                                            <div class="tag-enhanced preview-trigger" data-preview="{{ e($finalDietImage) }}" role="button" tabindex="0">
                                                {{ ucfirst($name) }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gallery Section -->
            @if (!empty($pet->gallery) && count($pet->gallery) > 0)
                <div class="container mx-auto px-4 md:px-6 py-12">
                    <h2 class="section-title text-3xl mb-8">Photo Gallery</h2>
                    
                    <div class="gallery-grid-enhanced">
                        @foreach ($pet->gallery as $index => $galleryItem)
                            @php
                                $raw = is_array($galleryItem) ? $galleryItem['url'] ?? null : $galleryItem;
                                if (empty($raw)) {
                                    $imgSrc = '/placeholder.svg?height=400&width=400';
                                } else {
                                    if (preg_match('/^https?:\/\//i', $raw)) {
                                        $imgSrc = $raw;
                                    } else {
                                        try {
                                            $imgSrc = \Illuminate\Support\Facades\Storage::url($raw);
                                        } catch (\Throwable $e) {
                                            $imgSrc = $raw;
                                        }
                                    }
                                }
                            @endphp

                            <div class="gallery-item-enhanced" @click="openLightbox('{{ e($imgSrc) }}')">
                                <img src="{{ e($imgSrc) }}" alt="{{ $pet->name }} - Image {{ $index + 1 }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Lightbox Modal -->
            <div x-show="lightboxOpen" x-cloak 
                x-transition.opacity
                class="fixed inset-0 z-[9999] bg-black/95 flex items-center justify-center p-4"
                @click="closeLightbox()">
                <button @click="closeLightbox()" 
                    class="absolute top-6 right-6 w-12 h-12 bg-white rounded-full flex items-center justify-center hover:bg-gray-100 transition z-10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <img :src="lightboxImage" 
                    alt="Gallery image" 
                    class="max-w-[90vw] max-h-[85vh] object-contain rounded-lg shadow-2xl"
                    @click.stop>
            </div>

            <!-- GIF Modal -->
            @if ($pet->gif_url)
                <div x-show="showGifModal" x-cloak
                    x-transition.opacity
                    class="fixed inset-0 z-[9999] bg-black/80 flex items-center justify-center p-4"
                    @click.self="showGifModal = false">
                    <div class="relative max-w-4xl w-full bg-white rounded-3xl overflow-hidden shadow-2xl animate-fade-in-up">
                        <button @click="showGifModal = false"
                            class="absolute top-4 right-4 z-10 w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-gray-100 transition shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <div class="flex flex-col md:flex-row">
                            <div class="flex-1 bg-gray-100 p-6 flex items-center justify-center">
                                <img src="{{ $pet->gif_url }}" 
                                    alt="GIF of {{ $pet->name }}"
                                    class="max-w-full max-h-[70vh] object-contain rounded-lg">
                            </div>
                            
                            <div class="md:w-80 p-8 bg-gradient-to-br from-purple-50 to-pink-50">
                                <h3 class="text-2xl font-bold mb-3">🎬 {{ $pet->name }} in Action</h3>
                                <p class="text-gray-700 mb-6">Watch this adorable clip showcasing the playful nature of {{ $pet->name }}!</p>
                                
                                <div class="space-y-3">
                                    <button @click="showGifModal = false" class="btn-primary-enhanced w-full justify-center">
                                        Close
                                    </button>
                                    @if ($pet->image_url)
                                    <a href="{{ $pet->image_url }}" target="_blank" class="btn-secondary-enhanced w-full justify-center">
                                        View Original
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Animate progress bars
                setTimeout(() => {
                    document.querySelectorAll('.progress-fill').forEach(el => {
                        const width = el.dataset.width;
                        el.style.width = width + '%';
                    });
                }, 300);

                // Text-to-Speech functionality
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

                playBtn?.addEventListener('click', function() {
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

                stopBtn?.addEventListener('click', stop);
                window.addEventListener('beforeunload', stop);

                // Anchored preview for color/diet tags
                (function() {
                    let anchoredEl = null;
                    let outsideHandler = null;

                    function ensureAnchored() {
                        if (anchoredEl) return anchoredEl;
                        anchoredEl = document.createElement('div');
                        anchoredEl.className = 'preview-card-enhanced';
                        anchoredEl.style.display = 'none';
                        anchoredEl.setAttribute('id', 'anchored-preview');
                        anchoredEl.innerHTML = '<img src="" alt="preview"><div class="content"><div class="title"></div></div>';
                        // store refs for later show/hide of content area when title is empty
                        anchoredEl._contentEl = anchoredEl.querySelector('.content');
                        anchoredEl._titleEl = anchoredEl.querySelector('.title');
                        document.body.appendChild(anchoredEl);
                        // keep open while hovering
                        anchoredEl.addEventListener('mouseenter', () => {
                            anchoredEl._hideTimeout && clearTimeout(anchoredEl._hideTimeout);
                        });
                        anchoredEl.addEventListener('mouseleave', () => {
                            anchoredEl._hideTimeout = setTimeout(hideAnchored, 150);
                        });
                        anchoredEl.addEventListener('click', (e) => {
                            e.stopPropagation();
                            const img = anchoredEl.querySelector('img');
                            const src = img?.getAttribute('src');
                            if (src) {
                                // try to call main Alpine openLightbox if available
                                try {
                                    const main = document.querySelector('main');
                                    if (main && main.__x && main.__x.$data && typeof main.__x.$data.openLightbox === 'function') {
                                        main.__x.$data.openLightbox(src);
                                    } else {
                                        window.open(src, '_blank');
                                    }
                                } catch (err) {
                                    window.open(src, '_blank');
                                }
                                hideAnchored();
                            }
                        });
                        return anchoredEl;
                    }

                    function showAnchored(url, triggerEl) {
                        if (!url) return;
                        const el = ensureAnchored();
                        const img = el.querySelector('img');
                        const title = el.querySelector('.title');
                        img.setAttribute('src', url);
                        // if title is empty, hide the content area to avoid large blank space
                        title.textContent = '';
                        if (el._contentEl) {
                            el._contentEl.style.display = title.textContent.trim() ? 'block' : 'none';
                        }
                        el.style.display = 'block';

                        const rect = triggerEl.getBoundingClientRect();
                        const previewWidth = Math.min(320, window.innerWidth - 32);
                        const previewHeight = 240;
                        let left = rect.left + (rect.width / 2) - (previewWidth / 2);
                        const maxLeft = window.innerWidth - previewWidth - 16;
                        if (left > maxLeft) left = maxLeft;
                        if (left < 16) left = 16;
                        let top = rect.top - previewHeight - 12;
                        if (top < 12) top = rect.bottom + 12;
                        if (top + previewHeight > window.innerHeight - 16) top = rect.top - previewHeight - 12;

                        el.style.left = left + 'px';
                        el.style.top = top + 'px';
                        el.style.width = previewWidth + 'px';

                        // outside click to hide
                        if (outsideHandler) document.removeEventListener('click', outsideHandler, true);
                        outsideHandler = function(e) {
                            if (!el.contains(e.target) && !triggerEl.contains(e.target)) {
                                hideAnchored();
                            }
                        };
                        document.addEventListener('click', outsideHandler, true);
                    }

                    function hideAnchored() {
                        if (!anchoredEl) return;
                        anchoredEl.style.display = 'none';
                        anchoredEl.querySelector('img').setAttribute('src', '');
                        if (outsideHandler) {
                            document.removeEventListener('click', outsideHandler, true);
                            outsideHandler = null;
                        }
                    }

                    // Delegate clicks from preview-trigger elements
                    document.body.addEventListener('click', function(e) {
                        const trigger = e.target.closest('.preview-trigger');
                        if (!trigger) return;
                        e.stopPropagation();
                        const url = trigger.getAttribute('data-preview');
                        if (!url) return;
                        showAnchored(url, trigger);
                    }, false);
                })();
            });
        </script>
    @endsection