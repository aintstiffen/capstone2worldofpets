{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'World of Pets - Discover Your Perfect Pet Companion')

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

        /* Hero Section Enhanced */
        .hero-enhanced {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-enhanced::before {
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

        /* Hero Image Cards */
        .hero-image-card {
            position: relative;
            overflow: hidden;
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hero-image-card::before {
            content: '';
            position: absolute;
            inset: -2px;
            background: var(--gradient-primary);
            border-radius: 24px;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .hero-image-card:hover::before {
            opacity: 0.4;
        }

        .hero-image-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
        }

        .hero-image-card img {
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hero-image-card:hover img {
            transform: scale(1.1) rotate(2deg);
        }

        /* Button Enhancements */
        .btn-primary-hero {
            background: var(--gradient-primary);
            color: white;
            border: none;
            box-shadow: var(--shadow-md);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-primary-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-primary-hero:hover::before {
            opacity: 1;
        }

        .btn-primary-hero:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary-hero {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-secondary-hero:hover {
            background: #667eea;
            color: white;
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        /* Feature Cards Enhanced */
        .feature-card-enhanced {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: var(--shadow-md);
            border-radius: 24px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .feature-card-enhanced::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--gradient-primary);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .feature-card-enhanced:hover::before {
            opacity: 0.05;
        }

        .feature-card-enhanced:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-lg);
            border-color: rgba(102, 126, 234, 0.3);
        }

        .feature-icon-bg {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15) 0%, rgba(118, 75, 162, 0.15) 100%);
            transition: all 0.3s ease;
        }

        .feature-card-enhanced:hover .feature-icon-bg {
            background: var(--gradient-primary);
            transform: scale(1.1) rotate(5deg);
        }

        .feature-card-enhanced:hover .feature-icon-bg svg {
            filter: brightness(0) invert(1);
        }

        /* Fun Facts Section Hotspots */
        .hotspot-funfacts {
            position: relative;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: transparent !important;
        }

        .hotspot-funfacts::before {
            content: '';
            position: absolute;
            inset: -8px;
            border-radius: 50%;
            background: rgba(240, 82, 82, 0.2);
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

        .hotspot-funfacts:hover {
            transform: scale(1.15);
        }

        .hotspot-label-funfacts {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(8px);
        }

        .tooltip-funfacts {
            background: white;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            border: 2px solid #667eea;
            z-index: 50;
        }

        /* Fun Facts Image Container */
        .funfacts-image-wrapper {
            position: relative;
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
        }

        .funfacts-image-wrapper::before {
            content: '';
            position: absolute;
            inset: -2px;
            background: var(--gradient-primary);
            border-radius: 32px;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .funfacts-image-wrapper:hover::before {
            opacity: 0.3;
        }

        /* Section Title Enhanced */
        .section-title-enhanced {
            position: relative;
            padding-left: 20px;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .section-title-enhanced::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 6px;
            background: var(--gradient-primary);
            border-radius: 3px;
        }

        /* Animation Classes */
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

        /* Link Hover Enhancement */
        .link-enhanced {
            color: #667eea;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
        }

        .link-enhanced::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient-primary);
            transition: width 0.3s ease;
        }

        .link-enhanced:hover::after {
            width: 100%;
        }

        .link-enhanced:hover {
            transform: translateX(4px);
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-col min-h-screen">
        {{-- Main Content --}}
        <main class="flex-1">
            {{-- Hero Section --}}
            <section class="hero-enhanced w-full py-12 md:py-24 lg:py-32">
                <div class="container mx-auto px-4 md:px-6">
                    <div class="grid gap-6 lg:grid-cols-2 lg:gap-12 items-center">
                        <div class="space-y-4 animate-fade-in-up stagger-1">
                            <h1 class="text-3xl font-bold tracking-tighter sm:text-4xl md:text-5xl bg-gradient-to-r from-[#667eea] to-[#764ba2] bg-clip-text text-transparent">
                                Discover Your Perfect Pet Companion
                            </h1>
                            <p class="text-gray-600 md:text-xl leading-relaxed">
                                Explore detailed profiles of popular dog and cat breeds in the Philippines. Find your ideal
                                pet match with our personality assessment tool.
                            </p>
                            <div class="flex flex-col gap-2 min-[400px]:flex-row pt-4">
                                <a href="{{ route('assessment') }}"
                                    class="btn-primary-hero inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold">
                                    Breed Recommendation
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                                <a href="/dogs" class="btn-secondary-hero inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold">
                                    Explore Breeds
                                </a>
                            </div>
                        </div>
                        <div class="mx-auto lg:ml-auto flex justify-center w-full animate-fade-in-up stagger-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full max-w-lg">
                                <div class="hero-image-card h-64 sm:h-72 md:h-80">
                                    <img src="https://imgs.search.brave.com/gTfFhk4oO_E73T8H6y_CNtbv_pzei0JoILJIucuWfCA/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9jZG4u/YnJpdGFubmljYS5j/b20vMzMvMTM2MTMz/LTAwNC0zMzg1RjZG/NS9nb2xkZW4tcmV0/cmlldmVyLmpwZw"
                                         alt="Golden Retriever" class="w-full h-full object-cover" />
                                </div>
                                <div class="hero-image-card h-64 sm:h-72 md:h-80">
                                    <img src="https://imgs.search.brave.com/6ylsIeMVYccyeHoDEgMwELzufk_vLNFuml9acMm3fTc/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9yZW5k/ZXIuZmluZWFydGFt/ZXJpY2EuY29tL2lt/YWdlcy9pbWFnZXMt/cHJvZmlsZS1mbG93/LzQwMC9pbWFnZXMt/bWVkaXVtLWxhcmdl/LTUvcGVyc2lhbi1j/YXQtc2lsdmVyc2Fs/dHBob3RvanNlbm9z/aWFpbi5qcGc"
                                         alt="Persian Cat" class="w-full h-full object-cover" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Features Section --}}
            <section class="w-full py-12 md:py-24 lg:py-32">
                <div class="container mx-auto px-4 md:px-6">
                    <div class="flex flex-col items-center justify-center space-y-4 text-center animate-fade-in-up stagger-1">
                        <div class="space-y-2">
                            <h2 class="section-title-enhanced text-center" style="padding-left: 0;">Our Features</h2>
                            <p class="max-w-[900px] text-gray-600 md:text-xl leading-relaxed">
                                Everything you need to learn about dog and cat breeds and find your perfect pet match.
                            </p>
                        </div>
                    </div>
                    <div class="mx-auto grid max-w-5xl grid-cols-1 gap-6 py-12 md:grid-cols-2 lg:grid-cols-3">
                        {{-- Feature Card 1 --}}
                        <div class="feature-card-enhanced p-6 animate-fade-in-up stagger-2">
                            <div class="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-2xl feature-icon-bg">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 220 120" class="w-12 h-12">
                                    <g>
                                        <circle cx="60" cy="60" r="35" fill="#667eea" />
                                        <ellipse cx="30" cy="50" rx="12" ry="20" fill="#764ba2" />
                                        <ellipse cx="90" cy="50" rx="12" ry="20" fill="#764ba2" />
                                        <circle cx="50" cy="55" r="5" fill="#fff" />
                                        <circle cx="70" cy="55" r="5" fill="#fff" />
                                        <circle cx="60" cy="70" r="4" fill="#fff" />
                                        <path d="M55 80 Q60 85 65 80" stroke="#fff" stroke-width="2" fill="none" />
                                    </g>
                                    <g>
                                        <circle cx="160" cy="60" r="30" fill="#f093fb" />
                                        <polygon points="140,40 145,20 155,45" fill="#f5576c" />
                                        <polygon points="180,40 175,20 165,45" fill="#f5576c" />
                                        <circle cx="150" cy="55" r="4" fill="#fff" />
                                        <circle cx="170" cy="55" r="4" fill="#fff" />
                                        <circle cx="160" cy="70" r="3" fill="#fff" />
                                        <path d="M155 78 Q160 83 165 78" stroke="#fff" stroke-width="2" fill="none" />
                                    </g>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Breed Profiles</h3>
                            <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                                Detailed information on 20 dog and 10 cat breeds including size, temperament, lifespan, and colors.
                            </p>
                            <div class="flex gap-4">
                                <a href="/dogs" class="link-enhanced text-sm">Dog Breeds</a>
                                <a href="/cats" class="link-enhanced text-sm">Cat Breeds</a>
                            </div>
                        </div>

                        {{-- Feature Card 2 --}}
                        <div class="feature-card-enhanced p-6 animate-fade-in-up stagger-3">
                            <div class="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-2xl feature-icon-bg">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 150" class="w-16 h-16">
                                    <g>
                                        <circle cx="80" cy="60" r="30" fill="#667eea" />
                                        <circle cx="68" cy="55" r="5" fill="#fff" />
                                        <circle cx="92" cy="55" r="5" fill="#fff" />
                                        <circle cx="80" cy="70" r="3" fill="#fff" />
                                        <path d="M70 80 Q80 90 90 80" stroke="#fff" stroke-width="2" fill="none" />
                                    </g>
                                    <g>
                                        <circle cx="240" cy="60" r="28" fill="#f093fb" />
                                        <circle cx="230" cy="55" r="4" fill="#fff" />
                                        <circle cx="250" cy="55" r="4" fill="#fff" />
                                        <circle cx="240" cy="68" r="2.5" fill="#fff" />
                                        <path d="M234 75 Q240 82 246 75" stroke="#fff" stroke-width="2" fill="none" />
                                    </g>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Personality Assessment</h3>
                            <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                                Take our interactive quiz to discover which dog or cat breeds match your personality and lifestyle.
                            </p>
                            <a href="/assessment" class="link-enhanced text-sm inline-block">Take Quiz</a>
                        </div>

                        {{-- Feature Card 3 --}}
                        <div class="feature-card-enhanced p-6 animate-fade-in-up stagger-4">
                            <div class="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-2xl feature-icon-bg">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" class="w-12 h-12">
                                    <circle cx="100" cy="100" r="95" stroke="#667eea" stroke-width="4" fill="none" />
                                    <circle cx="60" cy="100" r="30" fill="#667eea" />
                                    <circle cx="50" cy="95" r="5" fill="#fff" />
                                    <circle cx="70" cy="95" r="5" fill="#fff" />
                                    <path d="M55 110 Q60 120 65 110" stroke="#fff" stroke-width="2" fill="none" />
                                    <circle cx="140" cy="100" r="30" fill="#f093fb" />
                                    <circle cx="130" cy="95" r="5" fill="#fff" />
                                    <circle cx="150" cy="95" r="5" fill="#fff" />
                                    <path d="M135 110 Q140 120 145 110" stroke="#fff" stroke-width="2" fill="none" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Breed Comparison</h3>
                            <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                                Compare different breeds side-by-side to help you make an informed decision about your future pet.
                            </p>
                            <a href="/compare" class="link-enhanced text-sm inline-block">Compare Now</a>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Fun Facts Section --}}
            <section class="w-full py-12 md:py-24 lg:py-32 hero-enhanced">
                <div class="container mx-auto px-4 md:px-6">
                    <div class="grid gap-6 lg:grid-cols-3 items-center justify-center">
                        <div class="space-y-4 animate-fade-in-up stagger-1">
                            <h2 class="section-title-enhanced">Fun Facts Feature</h2>
                            <p class="text-gray-600 md:text-xl leading-relaxed">
                                <span class="md:inline hidden">Hover over</span>
                                <span class="md:hidden">Tap on</span>
                                different parts of a breed image to discover interesting facts about specific features.
                            </p>
                            <a href="/dogs/labrador-retriever" class="btn-primary-hero inline-flex items-center px-6 py-3 rounded-xl font-semibold">
                                Try It Now
                            </a>
                        </div>
                        <div class="mx-auto lg:ml-auto lg:col-span-2 flex flex-col items-center w-full max-w-md lg:max-w-3xl animate-fade-in-up stagger-2">
                            <div class="funfacts-image-wrapper relative w-full">
                                <img src="https://imgs.search.brave.com/7ad6u7NSwUDebItDOPca4slPF88rKp790UGkd9rpgC0/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWFn/ZXMudW5zcGxhc2gu/Y29tL3Bob3RvLTE1/MzcyMDQ2OTY0ODYt/OTY3ZjFiNzE5OGM4/P2ZtPWpwZyZxPTYw/Jnc9MzAwMCZpeGxp/Yj1yYi00LjEuMCZp/eGlkPU0zd3hNakEz/ZkRCOE1IeHpaV0Z5/WTJoOE0zeDhiR0Zp/Y21Ga2IzSjhaVzU4/TUh4OE1IeDhmREE9"
                                    alt="Labrador Retriever with interactive hotspots"
                                    class="rounded-2xl object-cover w-full h-auto" />

                                
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
@endsection