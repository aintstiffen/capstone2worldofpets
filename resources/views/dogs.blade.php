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

        /* Hero Section with Animated Background */
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

        /* Enhanced Breed Cards */
        .breed-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .breed-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(102, 126, 234, 0.3);
        }

        .breed-card-image {
            position: relative;
            aspect-ratio: 4/3;
            overflow: hidden;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
        }

        .breed-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .breed-card:hover .breed-card-image img {
            transform: scale(1.1);
        }

        .breed-card-image::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.3) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .breed-card:hover .breed-card-image::after {
            opacity: 1;
        }

        /* Price Badge */
        .price-badge {
            position: absolute;
            top: 16px;
            right: 16px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 14px;
            color: #667eea;
            box-shadow: var(--shadow-md);
            z-index: 2;
        }

        /* Info Pills */
        .info-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 16px;
            font-size: 13px;
            font-weight: 600;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            transition: all 0.2s ease;
        }

        .info-pill:hover {
            background: rgba(102, 126, 234, 0.2);
            transform: translateY(-2px);
        }

        /* Pagination Buttons */
        .pagination-btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .pagination-btn.active {
            background: var(--gradient-primary);
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .pagination-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Animation */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        .stagger-1 { animation-delay: 0.1s; opacity: 0; }
        .stagger-2 { animation-delay: 0.2s; opacity: 0; }
        .stagger-3 { animation-delay: 0.3s; opacity: 0; }
        .stagger-4 { animation-delay: 0.4s; opacity: 0; }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 24px;
            box-shadow: var(--shadow-sm);
        }

        .empty-state-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 24px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
        }

        /* Responsive Grid */
        @media (max-width: 768px) {
            .breed-card {
                border-radius: 16px;
            }
        }
    </style>
@endpush

@section('content')
<div class="flex flex-col min-h-screen">
    <!-- Hero Section -->
    <section class="hero-gradient py-16 md:py-24">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center max-w-4xl mx-auto animate-fade-in-up">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 bg-gradient-to-r from-[#667eea] to-[#764ba2] bg-clip-text text-transparent">
                    Discover Dog Breeds
                </h1>
                <p class="text-lg md:text-xl text-gray-600 mb-8 leading-relaxed">
                    Explore comprehensive profiles of the most beloved dog breeds in the Philippines.
                </p>
            </div>
        </div>
    </section>

    <!-- Breed Cards Section -->
    <main class="flex-1 py-12 md:py-20 bg-gray-50">
        <div class="container mx-auto px-4 md:px-6">
            @if(isset($pets) && $pets->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
                    @foreach($pets as $index => $pet)
                        <a href="{{ route('dogs.show', $pet->slug) }}" 
                           class="breed-card animate-fade-in-up stagger-{{ min($index + 1, 4) }}">
                            
                            <!-- Card Image -->
                            <div class="breed-card-image">
                                <img src="{{ $pet->image ? $pet->image_url : '/placeholder.svg?height=600&width=600' }}" 
                                     alt="{{ $pet->name }}" loading="lazy">
                                
                                <!-- Price Badge -->
                                @if($pet->price_min && $pet->price_max)
                                    <div class="price-badge">
                                        ₱{{ number_format($pet->price_min) }} - ₱{{ number_format($pet->price_max) }}
                                    </div>
                                @elseif($pet->price_range)
                                    <div class="price-badge">{{ $pet->price_range }}</div>
                                @endif
                            </div>

                            <!-- Card Content -->
                            <div class="p-6 flex-1 flex flex-col">
                                <!-- Breed Name -->
                                <h2 class="text-2xl font-bold mb-2 text-gray-900">{{ $pet->name }}</h2>
                                
                                <!-- Temperament -->
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                    {{ Str::limit($pet->temperament ?? $pet->description ?? 'Loyal and playful companion', 80) }}
                                </p>

                                <!-- Tags -->
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @if($pet->size)
                                        <span class="info-pill">📏 {{ $pet->size }}</span>
                                    @endif
                                    @if($pet->life_span)
                                        <span class="info-pill">⏳ {{ $pet->life_span }} yrs</span>
                                    @elseif($pet->lifespan)
                                        <span class="info-pill">⏳ {{ $pet->lifespan }}</span>
                                    @endif
                                </div>

                                <!-- Additional Info -->
                                <div class="mt-auto pt-4 border-t border-gray-100 space-y-2">
                                    @if($pet->origin)
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <span class="text-lg">🌍</span>
                                            <span class="font-medium">Origin:</span>
                                            <span>{{ $pet->origin }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($pet->energy_level)
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <span class="text-lg">⚡</span>
                                            <span class="font-medium">Energy:</span>
                                            <span>{{ $pet->energy_level }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($pet->friendliness)
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <span class="text-lg">😊</span>
                                            <span class="font-medium">Friendly:</span>
                                            <div class="flex gap-0.5">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $pet->friendliness ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                         fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.13 3.478a1 1 0 00.95.69h3.654c.969 0 1.371 1.24.588 1.81l-2.958 2.15a1 1 0 00-.364 1.118l1.13 3.478c.3.921-.755 1.688-1.54 1.118l-2.958-2.15a1 1 0 00-1.176 0l-2.958 2.15c-.784.57-1.838-.197-1.539-1.118l1.13-3.478a1 1 0 00-.364-1.118L2.38 8.905c-.783-.57-.38-1.81.588-1.81h3.654a1 1 0 00.95-.69l1.13-3.478z" />
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- View Details Button -->
                                <div class="mt-4 flex items-center justify-between text-sm font-semibold text-[#667eea]">
                                    <span>View Details</span>
                                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" 
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="empty-state animate-fade-in-up">
                    <div class="empty-state-icon">🐶</div>
                    <h3 class="text-2xl font-bold mb-3 text-gray-900">No Dog Breeds Found</h3>
                    <p class="text-gray-600 mb-6 max-w-md mx-auto">
                        We're currently updating our database with amazing dog breeds. Please check back soon!
                    </p>
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-full font-semibold text-white"
                       style="background: var(--gradient-primary);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Back to Home
                    </a>
                </div>
            @endif

            @if($pets->hasPages())
                <div class="mt-16">
                    <nav class="flex justify-center items-center gap-2 mb-6">
                        <button onclick="window.location='{{ $pets->previousPageUrl() }}'" 
                                class="pagination-btn {{ $pets->onFirstPage() ? 'opacity-50 cursor-not-allowed' : 'bg-white hover:bg-gray-50' }} border border-gray-300"
                                {{ $pets->onFirstPage() ? 'disabled' : '' }}>
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </button>
                        <div class="flex items-center gap-2">
                            @foreach ($pets->getUrlRange(1, $pets->lastPage()) as $page => $url)
                                <button onclick="window.location='{{ $url }}'" 
                                        class="pagination-btn {{ $page == $pets->currentPage() ? 'active' : 'bg-white hover:bg-gray-50 border border-gray-300' }}">
                                    {{ $page }}
                                </button>
                            @endforeach
                        </div>
                        <button onclick="window.location='{{ $pets->nextPageUrl() }}'" 
                                class="pagination-btn {{ !$pets->hasMorePages() ? 'opacity-50 cursor-not-allowed' : 'bg-white hover:bg-gray-50' }} border border-gray-300"
                                {{ !$pets->hasMorePages() ? 'disabled' : '' }}>
                            Next
                            <svg class="w-5 h-5 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </nav>
                    <div class="text-center">
                        <p class="text-sm text-gray-600 bg-white inline-block px-6 py-3 rounded-full shadow-sm">
                            Showing <span class="font-semibold text-[#667eea]">{{ $pets->firstItem() }}</span> 
                            to <span class="font-semibold text-[#667eea]">{{ $pets->lastItem() }}</span> 
                            of <span class="font-semibold text-[#667eea]">{{ $pets->total() }}</span> dog breeds
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </main>
</div>
@endsection