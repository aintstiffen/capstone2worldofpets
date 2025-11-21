@extends('layouts.app')

@section('title', 'Personality Assessment')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Custom Styles -->
    <style>
        :root {
            --pet-pink-50: #fff6f7;
            --pet-pink-100: #ffeef1;
            --pet-pink-200: #ffdfe6;
            --pet-primary: #ff637f;
            --pet-primary-600: #ff4f6b;
            --pet-accent: #ff9db7;
            --pet-muted: #6b7280;
            --shadow-lg: 0 18px 48px rgba(15, 23, 42, 0.06);
            --shadow-xl: 0 28px 72px rgba(15, 23, 42, 0.08);
        }

        body {
            background: var(--pet-pink-50);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .quiz-container {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(255, 250, 251, 0.98));
            border-radius: 18px;
            box-shadow: var(--shadow-lg);
            padding: 1.5rem;
            overflow-x: hidden;
            width: 100%;
            max-width: 100%;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
        }

        .gradient-text {
            background: linear-gradient(90deg, var(--pet-primary), var(--pet-accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-gradient {
            background: linear-gradient(90deg, var(--pet-primary), var(--pet-primary-600));
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            transition: all .22s ease;
            box-shadow: 0 8px 28px rgba(255, 99, 127, 0.12);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 99, 127, 0.6);
        }

        .btn-secondary {
            background: white;
            color: var(--pet-primary);
            border: 2px solid rgba(255, 99, 127, 0.12);
            padding: 10px 18px;
            border-radius: 12px;
            font-weight: 600;
        }

        .btn-secondary:hover {
            background: var(--pet-primary);
            color: white;
            transform: translateY(-2px);
        }

        .pet-card {
            background: white;
            border-radius: 16px;
            padding: 28px;
            transition: all .28s ease;
            cursor: pointer;
            border: 1px solid rgba(0, 0, 0, 0.04);
            position: relative;
            overflow: hidden;
        }

        .pet-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--pet-primary);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 0;
        }

        .pet-card:hover::before {
            opacity: 0.1;
        }

        .pet-card:hover {
            transform: translateY(-10px) scale(1.02);
            border-color: var(--pet-primary);
            box-shadow: var(--shadow-lg);
        }

        .pet-card>* {
            position: relative;
            z-index: 1;
        }

        .progress-container {
            background: rgba(255, 99, 127, 0.06);
            border-radius: 999px;
            height: 10px;
            overflow: hidden;
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--pet-primary), var(--pet-accent));
            height: 100%;
            border-radius: 999px;
            transition: width .45s ease;
            position: relative;
        }

        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .radio-label {
            position: relative;
            cursor: pointer;
            padding: 0.875rem;
            border-radius: 10px;
            transition: all .18s ease;
            background: white;
            border: 2px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 60px;
        }

        .radio-label input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .radio-label:not(.selected):hover {
            background: rgba(139, 92, 246, 0.08);
            transform: translateY(-2px);
            border-color: #8b5cf6;
        }

        .radio-label.selected {
            background: linear-gradient(135deg, #8b5cf6 0%, #d946ef 100%) !important;
            border-color: #8b5cf6 !important;
            color: white !important;
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(139, 92, 246, 0.3) !important;
        }

        .radio-label.selected span {
            color: white !important;
        }

        .radio-label.selected:hover {
            transform: scale(1.05) translateY(-2px) !important;
            background: linear-gradient(135deg, #7c3aed 0%, #c026d3 100%) !important;
        }

        .question-card {
            background: linear-gradient(180deg, #fff 0%, #fffbfc 100%);
            border-radius: 14px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            box-shadow: 0 6px 22px rgba(15, 23, 42, 0.04);
            width: 100%;
            overflow-y: auto;
            max-height: 45vh;
        }

        .options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.75rem;
        }

        @media (min-width: 640px) {
            .options-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .options-grid.has-5-options {
                grid-template-columns: repeat(5, 1fr);
            }
            
            .options-grid.has-3-options {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .question-card:hover {
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.12);
        }

        .question-text {
            font-size: 1rem;
            line-height: 1.5;
            color: #1f2937;
            margin-bottom: 1rem;
            font-weight: 600;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .breed-result-card {
            background: white;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            transition: all .3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .breed-result-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .breed-result-card .image-container {
            width: 100%;
            height: 220px;
            overflow: hidden;
            background: linear-gradient(135deg, var(--pet-pink-100), var(--pet-pink-200));
            position: relative;
        }

        .breed-result-card .breed-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.4s ease;
        }

        .breed-result-card:hover .breed-image {
            transform: scale(1.08);
        }

        .breed-result-card .content-wrapper {
            display: flex;
            flex-direction: column;
            flex: 1;
            padding: 1.5rem;
        }

        .breed-result-card .breed-info {
            flex: 1;
        }

        .breed-result-card .btn-gradient {
            margin-top: 1rem;
            width: 100%;
        }

        .trait-badge {
            background: linear-gradient(135deg, var(--pet-primary) 0%, var(--pet-accent) 100%);
            color: white;
            padding: 5px 12px;
            border-radius: 16px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
            margin: 3px;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .trait-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 99, 127, 0.4);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-in {
            animation: fadeIn 0.5s ease;
        }

        .emoji-icon {
            font-size: 64px;
            transition: transform .25s ease;
            display: inline-block;
        }

        .pet-card:hover .emoji-icon {
            transform: scale(1.2) rotate(5deg);
        }

        .section-badge {
            background: linear-gradient(90deg, #ffdfe6, #fff1f3);
            color: var(--pet-primary);
            padding: 8px 16px;
            border-radius: 999px;
            font-weight: 700;
            display: inline-block;
        }

        .hair-option-card {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            cursor: pointer;
        }

        .hair-option-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .size-option {
            transition: all 0.3s ease;
        }

        .size-option:hover {
            transform: scale(1.1) rotate(5deg);
        }

        .back-button {
            background: white;
            color: var(--pet-primary);
            border: 2px solid rgba(255, 99, 127, 0.08);
            padding: 0.625rem 1rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.875rem;
        }

        .back-button:hover {
            transform: translateX(-4px);
            border-color: rgba(255, 99, 127, 0.18);
        }

        .compatibility-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 700;
            display: inline-block;
        }

        @media (max-width: 768px) {
            .quiz-container {
                padding: 1rem;
                margin: 0 0.5rem;
                max-height: 90vh;
            }

            .gradient-text {
                font-size: 1.75rem !important;
                line-height: 1.1;
                word-break: break-word;
                white-space: normal;
            }

            .quiz-container h1 {
                font-size: 1.75rem !important;
                line-height: 1.1;
                word-break: break-word;
                white-space: normal;
            }

            .btn-gradient {
                font-size: 0.9rem;
                padding: 10px 0;
                width: 100%;
                min-width: 0;
            }
            
            .question-card {
                padding: 1rem;
                max-height: 50vh;
            }
            
            .options-grid {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
            
            .radio-label {
                padding: 0.75rem;
                min-height: 50px;
                font-size: 0.875rem;
            }
            
            .section-badge {
                padding: 6px 12px;
                font-size: 0.75rem;
            }

            .breed-result-card .image-container {
                height: 180px;
            }

            .breed-result-card .content-wrapper {
                padding: 1rem;
            }

            .breed-result-card h3 {
                font-size: 1rem;
            }
            
            .breed-result-card .bg-gradient-to-r {
                padding: 0.5rem;
            }
            
            .breed-result-card .bg-gradient-to-r p {
                font-size: 0.7rem;
            }

            .trait-badge {
                font-size: 10px;
                padding: 4px 10px;
                margin: 2px;
            }

            .compatibility-badge {
                font-size: 12px;
                padding: 6px 12px;
            }
        }

        @media (max-width: 640px) {
            .breed-result-card .image-container {
                height: 160px;
            }
            
            .compatibility-badge {
                font-size: 11px;
                padding: 5px 10px;
            }
        }
    </style>

    <div class="min-h-screen py-12 px-4" x-data="quizApp()">

        <div class="container max-w-5xl mx-auto">

            <!-- Introduction -->
            <template x-if="currentStage === 'intro'">
                <div class="quiz-container p-12 text-center animate-in">
                    <div class="mb-8">
                        <h1 class="text-6xl font-bold gradient-text mb-4">Breed Recommendations</h1>
                        <p class="text-xl text-gray-600">Discover your perfect pet companion through our veterinary-verified
                            assessment</p>
                    </div>

                    <div class="flex flex-col items-center gap-4 mt-12">
                        <button class="btn-gradient w-full max-w-md" @click="currentStage = 'petType'">
                            <span class="text-lg">Start Your Journey</span>
                        </button>

                        @auth
                            @if (auth()->user()->assessments->count() > 0)
                                <a href="{{ route('profile.edit') }}" class="btn-secondary w-full max-w-md text-center">
                                    View Your Past Results
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </template>

            <!-- Step 1: Choose pet type -->
            <template x-if="currentStage === 'petType'">
                <div class="quiz-container p-12 animate-in">
                    <div class="text-center mb-12">
                        <span class="section-badge">Step 1 of 2</span>
                        <h2 class="text-4xl font-bold gradient-text mt-4">Choose Your Pet Type</h2>
                        <p class="text-gray-600 mt-2">Which companion speaks to your heart?</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="mt-5 pet-card" @click="selectPetType('dog')">
                            <span class="emoji-icon">🐶</span>
                            <h3 class="text-2xl font-bold mt-4 text-gray-800">Dogs</h3>
                            <p class="text-gray-600 mt-2">Loyal, energetic, and always by your side</p>
                        </div>

                        <div class="mt-5 pet-card" @click="selectPetType('cat')">
                            <span class="emoji-icon">🐱</span>
                            <h3 class="text-2xl font-bold mt-4 text-gray-800">Cats</h3>
                            <p class="text-gray-600 mt-2">Independent, graceful, and affectionate</p>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Step 2: Lifestyle Questions -->
            <template x-if="currentStage === 'lifestyle'">
                <div class="quiz-container animate-in">
                    <div class="mb-4">
                        <span class="section-badge">Step 2 of 2</span>
                        <h2 class="text-2xl md:text-3xl font-bold gradient-text mt-2">Lifestyle Assessment</h2>
                        <p class="text-gray-600 mt-1 text-sm">
                            Question <span x-text="currentQuestion + 1"></span> of <span
                                x-text="lifestyleQuestions.length"></span>
                        </p>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                            <span>Overall Progress</span>
                            <span
                                x-text="Math.round(((currentQuestion + 1) / lifestyleQuestions.length) * 100) + '%'"></span>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar"
                                :style="`width: ${Math.round(((currentQuestion + 1) / lifestyleQuestions.length) * 100)}%`">
                            </div>
                        </div>
                    </div>

                    <!-- Current Question -->
                    <div class="question-card" style="flex: 1; overflow-y: auto;">
                        <h3 class="question-text" x-text="lifestyleQuestions[currentQuestion].question"></h3>

                        <div class="options-grid" 
                             :class="{
                                 'has-5-options': lifestyleQuestions[currentQuestion].options.length === 5,
                                 'has-3-options': lifestyleQuestions[currentQuestion].options.length === 3
                             }">
                            <template x-for="option in lifestyleQuestions[currentQuestion].options" :key="option.value">
                                <label class="radio-label cursor-pointer transition-all"
                                    :class="{'selected': lifestyleAnswers[lifestyleQuestions[currentQuestion].code] === option.value}"
                                    @click="lifestyleAnswers[lifestyleQuestions[currentQuestion].code] = option.value">
                                    <input type="radio" :name="'q' + currentQuestion" :value="option.value"
                                        x-model="lifestyleAnswers[lifestyleQuestions[currentQuestion].code]">
                                    <span class="text-sm font-medium" x-text="option.label"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- Warning Message -->
                    <div class="mt-3"
                        x-show="attemptedNext && !lifestyleAnswers[lifestyleQuestions[currentQuestion].code]">
                        <div class="bg-amber-100 border-l-4 border-amber-500 p-3 rounded-lg">
                            <p class="text-amber-800 font-semibold text-sm">
                                ⚠️ Please select an answer before continuing.
                            </p>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="mt-4 flex flex-col md:flex-row justify-between items-center gap-3" style="flex-shrink: 0;">
                        <div class="flex gap-3">
                            <button class="back-button" @click="prevQuestion()" x-show="currentQuestion > 0">
                                ← Previous
                            </button>
                            <button class="back-button" @click="currentStage = 'petType'">
                                Change Pet Type
                            </button>
                        </div>

                        <button class="btn-gradient" @click="nextQuestion()">
                            <span x-show="currentQuestion < lifestyleQuestions.length - 1">Next →</span>
                            <span x-show="currentQuestion === lifestyleQuestions.length - 1">See Results ✨</span>
                        </button>
                    </div>
                </div>
            </template>

            <!-- Results -->
            <template x-if="currentStage === 'results'">
                <div class="quiz-container p-8 md:p-12 animate-in">
                    <div class="text-center mb-12">
                        <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-4">Your Perfect Matches! 🎉</h2>
                        <p class="text-xl text-gray-600">Based on veterinary-verified compatibility analysis</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                        <template x-for="(breed, index) in recommendedBreeds" :key="index">
                            <div class="breed-result-card">
                                <div class="image-container">
                                    <img :src="getBreedImage(breed)" :alt="breed.name" class="breed-image"
                                        loading="lazy">
                                </div>
                                <div class="content-wrapper">
                                    <div class="breed-info">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h3 class="text-xl font-bold text-gray-800" x-text="breed.name"></h3>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    <span x-show="breed.rank === 1">🥇 Best Match</span>
                                                    <span x-show="breed.rank === 2">🥈 Great Alternative</span>
                                                    <span x-show="breed.rank === 3">🥉 Good Choice</span>
                                                </p>
                                            </div>
                                            <div class="text-center">
                                                <span class="compatibility-badge" x-text="breed.compatibility + '%'"></span>
                                                <p class="text-xs text-gray-500 mt-1">Match Score</p>
                                            </div>
                                        </div>
                                        
                                        <p class="text-gray-600 text-sm mb-4 leading-relaxed" x-text="truncateDescription(breed.description)">
                                        </p>

                                        <div class="mb-3">
                                            <div class="text-xs font-bold text-purple-600 mb-3 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                </svg>
                                                Why This Is Perfect For You:
                                            </div>
                                            <div class="space-y-2">
                                                <template x-for="(reason, i) in breed.detailedReasons" :key="i">
                                                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-3 rounded-lg border-l-4 border-purple-400">
                                                        <div class="flex items-start gap-2">
                                                            <span class="text-lg" x-text="reason.icon"></span>
                                                            <div class="flex-1">
                                                                <p class="text-xs font-bold text-gray-800" x-text="reason.title"></p>
                                                                <p class="text-xs text-gray-600 mt-1" x-text="reason.explanation"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>

                                    <a :href="'/' + petType + 's/' + breed.slug"
                                        class="btn-gradient block text-center">
                                        View Full Profile →
                                    </a>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="text-center space-y-4">
                        @auth
                            <button x-show="!resultsSaved" class="mt-3 btn-gradient" @click="saveResults">
                                💾 Save My Results
                            </button>

                            <div x-show="resultsSaved"
                                class="mt-3 inline-flex items-center gap-2 bg-green-500 text-white px-8 py-4 rounded-xl font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Results Saved Successfully!
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn-gradient inline-block">
                                🔐 Login to Save Your Results
                            </a>
                        @endauth

                        <button class="btn-secondary ml-4 mt-5" @click="restart">
                            🔄 Retake Assessment
                        </button>
                    </div>
                </div>
            </template>

        </div>
    </div>

    <!-- Alpine.js Logic -->
    <script>
        function quizApp() {
            return {
                currentStage: 'intro',
                petType: null,
                currentQuestion: 0,
                lifestyleAnswers: {},
                attemptedNext: false,
                resultsSaved: false,
                recommendedBreeds: [],

                // Optimized weights for maximum accuracy based on lifestyle compatibility research
                weights: {
                    T: 0.22, // Time - Most critical: determines if owner can meet pet's needs
                    L: 0.20, // Living Space - Essential: breed size must fit environment
                    A: 0.19, // Activity - Critical match: prevents behavioral issues
                    H: 0.15, // Allergy - Health priority: non-negotiable for allergic owners
                    F: 0.12, // Family - Safety factor: important for households with kids/elderly
                    E: 0.06, // Experience - Helpful but adaptable with training
                    S: 0.10, // Sociability - Personality fit matters
                    I: 0.08, // Indoor/Outdoor - Adaptable for most breeds
                    P: 0.03  // Purpose - Least critical: most pets serve multiple roles
                },

                lifestyleQuestions: [{
                        question: "How much time can you dedicate to your pet daily?",
                        code: "T",
                        options: [{
                                label: "Less than 2 hours per day",
                                value: 1
                            },
                            {
                                label: "2-3 hours per day",
                                value: 2
                            },
                            {
                                label: "4-6 hours per day",
                                value: 3
                            },
                            {
                                label: "6-8 hours per day",
                                value: 4
                            },
                            {
                                label: "Most of the day (8+ hours)",
                                value: 5
                            }
                        ]
                    },
                    {
                        question: "What type of living space do you have?",
                        code: "L",
                        options: [{
                                label: "Small apartment (studio or 1-bedroom)",
                                value: 1
                            },
                            {
                                label: "Medium apartment or condo",
                                value: 2
                            },
                            {
                                label: "Townhouse or small house",
                                value: 3
                            },
                            {
                                label: "House with backyard",
                                value: 4
                            },
                            {
                                label: "Large property or farm",
                                value: 5
                            }
                        ]
                    },
                    {
                        question: "What is your daily activity level?",
                        code: "A",
                        options: [{
                                label: "Sedentary (mostly indoors, minimal movement)",
                                value: 1
                            },
                            {
                                label: "Light activity (occasional short walks)",
                                value: 2
                            },
                            {
                                label: "Moderately active (regular walks or exercise)",
                                value: 3
                            },
                            {
                                label: "Very active (daily exercise routine)",
                                value: 4
                            },
                            {
                                label: "Highly active (outdoor enthusiast, athlete)",
                                value: 5
                            }
                        ]
                    },
                    {
                        question: "Who else lives in your household?",
                        code: "F",
                        options: [{
                                label: "Just me (or adults only)",
                                value: 2
                            },
                            {
                                label: "Elderly family members",
                                value: 3
                            },
                            {
                                label: "Children and elderly members",
                                value: 4
                            },
                            {
                                label: "Young children (under 10 years old)",
                                value: 5
                            }
                        ]
                    },
                    {
                        question: "What is your experience level with pet ownership?",
                        code: "E",
                        options: [{
                                label: "First-time pet owner (no experience)",
                                value: 1
                            },
                            {
                                label: "Beginner (limited experience)",
                                value: 2
                            },
                            {
                                label: "Some experience (owned pets before)",
                                value: 3
                            },
                            {
                                label: "Experienced owner (multiple pets)",
                                value: 4
                            },
                            {
                                label: "Expert (trainer/breeder/professional)",
                                value: 5
                            }
                        ]
                    },
                    {
                        question: "Does anyone in your household have pet allergies?",
                        code: "H",
                        options: [{
                                label: "Yes, someone has allergies",
                                value: 1
                            },
                            {
                                label: "No, no one has allergies",
                                value: 5
                            }
                        ]
                    },
                    {
                        question: "What personality type do you prefer in a pet?",
                        code: "S",
                        options: [{
                                label: "Very independent (does their own thing)",
                                value: 1
                            },
                            {
                                label: "Somewhat independent (occasional interaction)",
                                value: 2
                            },
                            {
                                label: "Balanced (friendly but not needy)",
                                value: 3
                            },
                            {
                                label: "Social and friendly (enjoys company)",
                                value: 4
                            },
                            {
                                label: "Very affectionate (constant companion)",
                                value: 5
                            }
                        ]
                    },
                    {
                        question: "What is your primary reason for getting a pet?",
                        code: "P",
                        options: [{
                                label: "Protection and security",
                                value: 1
                            },
                            {
                                label: "Companionship and friendship",
                                value: 2
                            },
                            {
                                label: "Active lifestyle partner",
                                value: 3
                            },
                            {
                                label: "Emotional support and comfort",
                                value: 4
                            },
                            {
                                label: "Show, breeding, or competition",
                                value: 5
                            }
                        ]
                    },
                    {
                        question: "Where will your pet primarily live?",
                        code: "I",
                        options: [{
                                label: "Primarily outdoors (yard, kennel)",
                                value: 1
                            },
                            {
                                label: "Both indoor and outdoor (flexible)",
                                value: 3
                            },
                            {
                                label: "Primarily indoors (house pet)",
                                value: 5
                            }
                        ]
                    }
                ],

                breedProfiles: {
                    dog: {
                        // Local breed - versatile, adaptable
                        'Aspin': {
                            T: 3,  // Moderate time needs
                            L: 3,  // Medium space - adaptable
                            A: 3,  // Moderate activity
                            F: 5,  // Excellent with families
                            E: 1,  // Perfect for beginners
                            H: 5,  // Hypoallergenic-friendly
                            S: 4,  // Social and friendly
                            P: 2,  // Companionship
                            I: 2   // Can do both, prefers some outdoor
                        },

                        // Small indoor breeds
                        'Shih Tzu': {
                            T: 3,  // Needs companionship but not demanding
                            L: 1,  // Perfect for small spaces
                            A: 2,  // Low-moderate energy
                            F: 5,  // Excellent with families
                            E: 2,  // Good for beginners
                            H: 3,  // Moderate shedding
                            S: 5,  // Very affectionate
                            P: 2,  // Companionship
                            I: 5   // Indoor breed
                        },
                        'Pomeranian': {
                            T: 3,  // Moderate attention needs
                            L: 1,  // Small space friendly
                            A: 3,  // Moderate energy
                            F: 3,  // Can be good with older kids
                            E: 2,  // Fairly easy for beginners
                            H: 2,  // Heavy shedder
                            S: 5,  // Very attached to owners
                            P: 2,  // Companionship
                            I: 5   // Indoor
                        },
                        'Japanese Spitz': {
                            T: 3,  // Moderate needs
                            L: 2,  // Small to medium space
                            A: 3,  // Active but manageable
                            F: 5,  // Great with families
                            E: 2,  // Beginner friendly
                            H: 3,  // Moderate allergen
                            S: 5,  // Very social
                            P: 2,  // Companion
                            I: 4   // Mostly indoor
                        },
                        'Pug': {
                            T: 2,  // Low maintenance
                            L: 1,  // Small space perfect
                            A: 2,  // Low energy
                            F: 5,  // Excellent with everyone
                            E: 1,  // Very beginner friendly
                            H: 4,  // Low shedding
                            S: 5,  // Extremely social
                            P: 2,  // Companion
                            I: 5   // Indoor
                        },
                        'Chihuahua': {
                            T: 3,  // Needs attention
                            L: 1,  // Tiny space OK
                            A: 2,  // Low-moderate energy
                            F: 3,  // Better with adults/older kids
                            E: 2,  // Needs some training
                            H: 4,  // Low allergen
                            S: 4,  // Very bonded to owner
                            P: 2,  // Companion
                            I: 5   // Indoor
                        },
                        'French Bulldog': {
                            T: 3,  // Moderate attention
                            L: 1,  // Apartment perfect
                            A: 2,  // Low energy
                            F: 5,  // Great with families
                            E: 1,  // Very easy
                            H: 4,  // Minimal shedding
                            S: 5,  // Very affectionate
                            P: 2,  // Companion
                            I: 5   // Indoor
                        },
                        'Boston Terrier': {
                            T: 3,  // Moderate needs
                            L: 2,  // Small-medium space
                            A: 3,  // Moderate energy
                            F: 5,  // Excellent with families
                            E: 2,  // Beginner friendly
                            H: 4,  // Low shedding
                            S: 4,  // Friendly
                            P: 2,  // Companion
                            I: 4   // Mostly indoor
                        },
                        'Dachshund': {
                            T: 3,  // Moderate time
                            L: 2,  // Small space OK
                            A: 3,  // Moderate activity
                            F: 4,  // Good with families
                            E: 2,  // Needs training
                            H: 4,  // Low allergen
                            S: 4,  // Loyal and loving
                            P: 2,  // Companion
                            I: 4   // Can do both
                        },

                        // Medium breeds
                        'Beagle': {
                            T: 4,  // Needs attention and exercise
                            L: 3,  // Medium space needed
                            A: 4,  // High energy
                            F: 5,  // Excellent with families
                            E: 2,  // Trainable but stubborn
                            H: 4,  // Low allergen
                            S: 5,  // Very social
                            P: 3,  // Active companion
                            I: 3   // Needs outdoor time
                        },
                        'Poodle': {
                            T: 4,  // Needs mental stimulation
                            L: 2,  // Adaptable to small spaces
                            A: 3,  // Moderate-high energy
                            F: 5,  // Great with families
                            E: 3,  // Smart, needs training
                            H: 5,  // Hypoallergenic
                            S: 5,  // Very social
                            P: 3,  // Versatile
                            I: 4   // Mostly indoor
                        },

                        // Large active breeds
                        'Labrador Retriever': {
                            T: 4,  // High time commitment
                            L: 4,  // Needs space
                            A: 4,  // High energy
                            F: 5,  // Perfect family dog
                            E: 2,  // Easy to train
                            H: 3,  // Moderate shedding
                            S: 5,  // Extremely social
                            P: 3,  // Active companion
                            I: 3   // Indoor/outdoor balance
                        },
                        'Boxer': {
                            T: 4,  // Needs attention
                            L: 4,  // Needs space
                            A: 4,  // High energy
                            F: 5,  // Great with kids
                            E: 3,  // Needs consistent training
                            H: 3,  // Moderate allergen
                            S: 5,  // Very social
                            P: 2,  // Companion/protection
                            I: 3   // Needs outdoor time
                        },
                        'German Shepherd': {
                            T: 5,  // High time needs
                            L: 5,  // Needs lots of space
                            A: 5,  // Very high energy
                            F: 4,  // Good with families when trained
                            E: 4,  // Needs experienced handler
                            H: 3,  // Moderate shedding
                            S: 3,  // Loyal but selective
                            P: 1,  // Working/protection
                            I: 2   // Needs outdoor activity
                        },
                        'Dobermann': {
                            T: 5,  // High commitment
                            L: 5,  // Large space needed
                            A: 5,  // Very active
                            F: 3,  // Needs supervision with kids
                            E: 4,  // Experienced owners
                            H: 3,  // Moderate allergen
                            S: 3,  // Loyal to family
                            P: 1,  // Protection
                            I: 2   // Outdoor oriented
                        },
                        'Bullmastiff': {
                            T: 4,  // Moderate-high time
                            L: 5,  // Large space
                            A: 3,  // Moderate energy
                            F: 4,  // Good with families
                            E: 3,  // Needs firm training
                            H: 3,  // Moderate shedding
                            S: 3,  // Calm and loyal
                            P: 1,  // Guard dog
                            I: 3   // Indoor/outdoor
                        },

                        // Working/outdoor breeds
                        'Siberian Husky': {
                            T: 5,  // Very high time needs
                            L: 5,  // Large space essential
                            A: 5,  // Extremely active
                            F: 4,  // Good with families
                            E: 4,  // Experienced owners needed
                            H: 2,  // Heavy shedder
                            S: 4,  // Social and friendly
                            P: 3,  // Working/active
                            I: 1   // Outdoor breed
                        },
                        'Alaskan Malamute': {
                            T: 5,  // Very demanding
                            L: 5,  // Large space needed
                            A: 5,  // Extremely active
                            F: 4,  // Good with families
                            E: 4,  // Experienced handlers
                            H: 2,  // Heavy shedding
                            S: 4,  // Social
                            P: 3,  // Working
                            I: 1   // Outdoor
                        },
                        'Akita': {
                            T: 4,  // High commitment
                            L: 5,  // Large space
                            A: 4,  // Active
                            F: 3,  // Selective with strangers
                            E: 5,  // Expert level
                            H: 2,  // Heavy shedding
                            S: 2,  // Independent
                            P: 1,  // Guard/protection
                            I: 3   // Can do both
                        },
                        'Chow Chow': {
                            T: 3,  // Moderate time
                            L: 4,  // Medium-large space
                            A: 2,  // Low energy
                            F: 3,  // Selective
                            E: 4,  // Needs experienced owner
                            H: 1,  // Heavy shedding
                            S: 2,  // Independent
                            P: 1,  // Guard
                            I: 3   // Indoor/outdoor
                        }
                    },
                    cat: {
                        'Puspin': {
                            T: 2,  // Low maintenance
                            L: 2,  // Small space OK
                            A: 3,  // Moderate activity
                            F: 5,  // Great with families
                            E: 1,  // Perfect for beginners
                            H: 5,  // Hypoallergenic friendly
                            S: 3,  // Moderate affection
                            P: 2,  // Companion
                            I: 2   // Adaptable
                        },
                        'Siamese': {
                            T: 3,  // Needs attention
                            L: 2,  // Small space OK
                            A: 3,  // Active
                            F: 4,  // Good with families
                            E: 2,  // Beginner friendly
                            H: 3,  // Moderate allergen
                            S: 5,  // Very vocal and social
                            P: 2,  // Companion
                            I: 5   // Indoor
                        },
                        'Russian Blue': {
                            T: 2,  // Low maintenance
                            L: 2,  // Small space perfect
                            A: 2,  // Low-moderate energy
                            F: 4,  // Good with families
                            E: 2,  // Easy
                            H: 5,  // Hypoallergenic
                            S: 3,  // Reserved but loving
                            P: 2,  // Companion
                            I: 5   // Indoor
                        },
                        'British Shorthair': {
                            T: 2,  // Low maintenance
                            L: 2,  // Apartment friendly
                            A: 2,  // Low energy
                            F: 5,  // Excellent with families
                            E: 1,  // Very easy
                            H: 4,  // Low allergen
                            S: 3,  // Calm and gentle
                            P: 2,  // Companion
                            I: 5   // Indoor
                        },
                        'Exotic Shorthair': {
                            T: 2,  // Low maintenance
                            L: 2,  // Small space
                            A: 2,  // Low energy
                            F: 5,  // Great with families
                            E: 1,  // Easy
                            H: 4,  // Low shedding
                            S: 4,  // Affectionate
                            P: 2,  // Companion
                            I: 5   // Indoor
                        },
                        'Maine Coon': {
                            T: 3,  // Moderate time
                            L: 4,  // Needs more space
                            A: 3,  // Moderate activity
                            F: 5,  // Excellent with families
                            E: 2,  // Beginner friendly
                            H: 2,  // Heavy shedding
                            S: 5,  // Very social
                            P: 2,  // Companion
                            I: 3   // Can do both
                        },
                        'American Shorthair': {
                            T: 2,  // Low maintenance
                            L: 2,  // Adaptable
                            A: 3,  // Moderate energy
                            F: 5,  // Perfect for families
                            E: 1,  // Very easy
                            H: 4,  // Low allergen
                            S: 4,  // Friendly
                            P: 2,  // Companion
                            I: 4   // Mostly indoor
                        },
                        'Ragdoll': {
                            T: 3,  // Needs companionship
                            L: 3,  // Medium space
                            A: 1,  // Very low energy
                            F: 5,  // Excellent with families
                            E: 1,  // Very easy
                            H: 3,  // Moderate allergen
                            S: 5,  // Extremely affectionate
                            P: 4,  // Emotional support
                            I: 5   // Indoor only
                        },
                        'Persian': {
                            T: 3,  // Needs attention
                            L: 2,  // Small space OK
                            A: 1,  // Very low energy
                            F: 4,  // Good with gentle families
                            E: 2,  // Needs some grooming knowledge
                            H: 2,  // Heavy shedding
                            S: 4,  // Affectionate
                            P: 4,  // Lap cat
                            I: 5   // Indoor
                        },
                        'Himalayan': {
                            T: 3,  // Moderate attention
                            L: 2,  // Small space
                            A: 1,  // Very low energy
                            F: 4,  // Gentle with families
                            E: 2,  // Needs grooming
                            H: 2,  // Heavy shedding
                            S: 5,  // Very affectionate
                            P: 4,  // Lap cat
                            I: 5   // Indoor
                        }
                    }
                },

                selectPetType(type) {
                    this.petType = type;
                    this.currentStage = 'lifestyle';
                },

                nextQuestion() {
                    const currentCode = this.lifestyleQuestions[this.currentQuestion].code;
                    if (!this.lifestyleAnswers[currentCode]) {
                        this.attemptedNext = true;
                        return;
                    }

                    this.attemptedNext = false;
                    if (this.currentQuestion < this.lifestyleQuestions.length - 1) {
                        this.currentQuestion++;
                    } else {
                        this.calculateResults();
                        this.currentStage = 'results';
                    }
                },

                prevQuestion() {
                    this.attemptedNext = false;
                    if (this.currentQuestion > 0) {
                        this.currentQuestion--;
                    } else {
                        this.currentStage = 'petType';
                    }
                },

                calculateResults() {
                    console.log('User Lifestyle Answers:', this.lifestyleAnswers);

                    const breeds = this.breedProfiles[this.petType];
                    let allBreeds = Object.keys(breeds);
                    let candidates = [...allBreeds];

                    // INTELLIGENT FILTERING SYSTEM - Progressive filters with fallback
                    const minCandidates = 5; // Ensure enough options for comparison
                    
                    // Filter 1: CRITICAL - Allergy (absolute requirement)
                    if (this.lifestyleAnswers.H === 1) {
                        const allergyFiltered = candidates.filter(name => breeds[name].H >= 4);
                        if (allergyFiltered.length >= 3) {
                            candidates = allergyFiltered;
                            console.log('✓ Allergy filter applied:', candidates.length, 'breeds');
                        } else {
                            console.warn('⚠ Allergy filter too restrictive, relaxed to H >= 3');
                            const relaxedAllergy = candidates.filter(name => breeds[name].H >= 3);
                            if (relaxedAllergy.length >= 3) {
                                candidates = relaxedAllergy;
                            }
                        }
                    }

                    // Filter 2: Living Space - strict for very small spaces only
                    if (this.lifestyleAnswers.L === 1) {
                        const spaceFiltered = candidates.filter(name => breeds[name].L <= 2);
                        if (spaceFiltered.length >= minCandidates) {
                            candidates = spaceFiltered;
                            console.log('✓ Small space filter applied:', candidates.length, 'breeds');
                        }
                    }

                    // Filter 3: Time - critical filter for very limited availability
                    if (this.lifestyleAnswers.T === 1) {
                        const timeFiltered = candidates.filter(name => breeds[name].T <= 2);
                        if (timeFiltered.length >= minCandidates) {
                            candidates = timeFiltered;
                            console.log('✓ Low time filter applied:', candidates.length, 'breeds');
                        }
                    }

                    // Filter 4: Experience - first-time owners should avoid difficult breeds
                    if (this.lifestyleAnswers.E === 1) {
                        const expFiltered = candidates.filter(name => breeds[name].E <= 2);
                        if (expFiltered.length >= minCandidates) {
                            candidates = expFiltered;
                            console.log('✓ Beginner-friendly filter applied:', candidates.length, 'breeds');
                        }
                    }

                    // Ensure minimum candidates for meaningful comparison
                    if (candidates.length < 3) {
                        console.warn('⚠ Too few candidates after filtering, expanding pool');
                        candidates = allBreeds;
                    }

                    console.log('Final candidate pool:', candidates.length, 'breeds');
                    console.log('Final candidate pool:', candidates.length, 'breeds');

                    // ENHANCED SCORING ALGORITHM with Multi-Factor Analysis
                    // ENHANCED SCORING ALGORITHM with Multi-Factor Analysis
                    const scored = candidates.map(name => {
                        const breed = breeds[name];
                        let totalScore = 0;
                        let criticalFactorScore = 0;

                        // Calculate weighted distance for each factor
                        ['T', 'L', 'A', 'H', 'F', 'E', 'S', 'I', 'P'].forEach(factor => {
                            const userVal = this.lifestyleAnswers[factor] ?? 3;
                            const breedVal = breed[factor] ?? 3;
                            const weight = this.weights[factor] ?? 0;
                            
                            // Calculate normalized difference (0-5 scale)
                            const maxDiff = 5;
                            const diff = Math.abs(userVal - breedVal);
                            const normalizedDiff = diff / maxDiff;
                            
                            // Calculate weighted score (inverse of difference)
                            const factorScore = (1 - normalizedDiff) * weight;
                            totalScore += factorScore;
                            
                            // Track critical factors separately (T, L, A, H)
                            if (['T', 'L', 'A', 'H'].includes(factor)) {
                                criticalFactorScore += factorScore;
                            }
                        });

                        // ENHANCED BONUS SCORING SYSTEM
                        let bonusScore = 0;
                        
                        // Critical Factor Bonuses (higher weight)
                        
                        // Perfect allergy match - CRITICAL
                        if (this.lifestyleAnswers.H === 1 && breed.H >= 4) {
                            bonusScore += 0.08; // Doubled from 0.04
                        }
                        
                        // Perfect space match - VERY IMPORTANT
                        const spaceDiff = Math.abs(this.lifestyleAnswers.L - breed.L);
                        if (spaceDiff === 0) bonusScore += 0.06;
                        else if (spaceDiff === 1) bonusScore += 0.03;
                        
                        // Perfect activity match - CRITICAL for happiness
                        const activityDiff = Math.abs(this.lifestyleAnswers.A - breed.A);
                        if (activityDiff === 0) bonusScore += 0.06;
                        else if (activityDiff === 1) bonusScore += 0.03;
                        
                        // Perfect time match - ESSENTIAL
                        const timeDiff = Math.abs(this.lifestyleAnswers.T - breed.T);
                        if (timeDiff === 0) bonusScore += 0.05;
                        else if (timeDiff === 1) bonusScore += 0.02;
                        
                        // Secondary Factor Bonuses
                        
                        // Family safety match
                        if (this.lifestyleAnswers.F >= 4 && breed.F >= 4) bonusScore += 0.04;
                        
                        // Experience match for beginners
                        if (this.lifestyleAnswers.E <= 2 && breed.E <= 2) bonusScore += 0.03;
                        
                        // Personality compatibility
                        if (Math.abs(this.lifestyleAnswers.S - breed.S) <= 1) bonusScore += 0.02;
                        
                        // Indoor/outdoor match
                        if (Math.abs(this.lifestyleAnswers.I - breed.I) <= 1) bonusScore += 0.02;

                        // Calculate final compatibility percentage (realistic scale)
                        // Base score from weighted factors (0-1 range)
                        // Bonuses can add up to ~0.35 max, giving totalScore range of ~0-1.35
                        // Scale to 50-100% range for better user interpretation
                        const rawScore = totalScore + bonusScore;
                        
                        // Map score to realistic percentage:
                        // Perfect match (1.35+) = 95-100%
                        // Excellent match (1.15-1.35) = 85-94%
                        // Good match (0.95-1.15) = 75-84%
                        // Fair match (0.75-0.95) = 65-74%
                        // Poor match (<0.75) = 50-64%
                        let compatibility;
                        if (rawScore >= 1.25) {
                            compatibility = Math.round(95 + (rawScore - 1.25) * 50); // 95-100%
                        } else if (rawScore >= 1.10) {
                            compatibility = Math.round(85 + (rawScore - 1.10) * 66.67); // 85-94%
                        } else if (rawScore >= 0.90) {
                            compatibility = Math.round(75 + (rawScore - 0.90) * 50); // 75-84%
                        } else if (rawScore >= 0.70) {
                            compatibility = Math.round(65 + (rawScore - 0.70) * 50); // 65-74%
                        } else {
                            compatibility = Math.round(50 + rawScore * 21.43); // 50-64%
                        }
                        
                        compatibility = Math.max(50, Math.min(100, compatibility));

                        // Calculate distance for tiebreaking (weighted Euclidean)
                        let sumSquaredDiff = 0;
                        ['T', 'L', 'A', 'H', 'F', 'E', 'S', 'I', 'P'].forEach(factor => {
                            const userVal = this.lifestyleAnswers[factor] ?? 3;
                            const breedVal = breed[factor] ?? 3;
                            const diff = userVal - breedVal;
                            const weight = this.weights[factor] ?? 0;
                            sumSquaredDiff += weight * diff * diff;
                        });
                        const distance = Math.sqrt(sumSquaredDiff);

                        return {
                            name,
                            distance,
                            compatibility,
                            rawScore: totalScore + bonusScore,
                            criticalFactorScore,
                            profile: breed
                        };
                    });

                    // Sort by multiple criteria for best accuracy
                    scored.sort((a, b) => {
                        // 1. First priority: Compatibility score difference > 5%
                        const compatDiff = b.compatibility - a.compatibility;
                        if (Math.abs(compatDiff) > 5) {
                            return compatDiff;
                        }
                        
                        // 2. Second priority: Critical factor score (T, L, A, H)
                        const criticalDiff = b.criticalFactorScore - a.criticalFactorScore;
                        if (Math.abs(criticalDiff) > 0.02) {
                            return criticalDiff;
                        }
                        
                        // 3. Third priority: Overall distance (lower is better)
                        return a.distance - b.distance;
                    });

                    console.log('🏆 Top 5 matches:', scored.slice(0, 5).map(s => ({
                        name: s.name,
                        compatibility: s.compatibility + '%',
                        rawScore: s.rawScore.toFixed(3),
                        distance: s.distance.toFixed(3),
                        criticalScore: (s.criticalFactorScore * 100).toFixed(1) + '%'
                    })));

                    // GUARANTEE EXACTLY 3 BREEDS
                    let top3 = scored.slice(0, 3);
                    
                    // If we somehow have less than 3, fill with next best matches
                    while (top3.length < 3 && allBreeds.length >= 3) {
                        const remaining = allBreeds.filter(name => 
                            !top3.find(t => t.name === name)
                        );
                        if (remaining.length > 0) {
                            const fallback = {
                                name: remaining[0],
                                distance: 999,
                                compatibility: 65,
                                profile: breeds[remaining[0]]
                            };
                            top3.push(fallback);
                        } else {
                            break;
                        }
                    }

                    // Get backend breed data and generate match reasons
                    let masterBreeds = [];
                    try {
                        if (this.petType === 'dog') {
                            masterBreeds = JSON.parse('{!! addslashes($dogBreeds ?? '[]') !!}');
                        } else {
                            masterBreeds = JSON.parse('{!! addslashes($catBreeds ?? '[]') !!}');
                        }
                    } catch (e) {
                        console.error('Error loading breed data:', e);
                    }

                    this.recommendedBreeds = top3.map((match, index) => {
                        const breedData = masterBreeds.find(b =>
                            b.name.toLowerCase() === match.name.toLowerCase()
                        );

                        const detailedReasons = this.generateMatchReasons(match.profile);

                        return {
                            id: breedData?.id || null,
                            name: match.name,
                            slug: breedData?.slug || match.name.toLowerCase().replace(/\s+/g, '-'),
                            image: breedData?.image || '/placeholder.svg?height=300&width=400',
                            description: breedData?.description || 'A wonderful companion for you.',
                            compatibility: match.compatibility,
                            detailedReasons: detailedReasons,
                            distance: match.distance,
                            rank: index + 1
                        };
                    });

                    console.log('Final 3 recommendations:', this.recommendedBreeds);
                },

                generateMatchReasons(breedProfile) {
                    const detailedReasons = [];

                    // Check time commitment
                    if (Math.abs(this.lifestyleAnswers.T - breedProfile.T) <= 1) {
                        const userTime = this.lifestyleAnswers.T;
                        let timeExplanation = '';
                        if (userTime <= 2) {
                            timeExplanation = 'This breed is independent and can handle being alone during your work hours. Perfect for busy schedules!';
                        } else if (userTime <= 3) {
                            timeExplanation = 'This breed needs moderate attention and will thrive with your available time for daily care and play.';
                        } else {
                            timeExplanation = 'This breed loves companionship and will enjoy spending lots of quality time with you every day.';
                        }
                        detailedReasons.push({
                            icon: '⏰',
                            title: 'Time Commitment Match',
                            explanation: timeExplanation
                        });
                    }

                    // Check living space
                    if (Math.abs(this.lifestyleAnswers.L - breedProfile.L) <= 1) {
                        const userSpace = this.lifestyleAnswers.L;
                        let spaceExplanation = '';
                        if (userSpace <= 2) {
                            spaceExplanation = 'This breed adapts well to apartment living and doesn\'t need a large yard. Great for smaller spaces!';
                        } else if (userSpace <= 3) {
                            spaceExplanation = 'This breed fits perfectly in your home size with enough room to move around comfortably.';
                        } else {
                            spaceExplanation = 'This breed will love having plenty of space to roam and explore in your large home or yard.';
                        }
                        detailedReasons.push({
                            icon: '🏠',
                            title: 'Perfect Home Size Match',
                            explanation: spaceExplanation
                        });
                    }

                    // Check activity level
                    if (Math.abs(this.lifestyleAnswers.A - breedProfile.A) <= 1) {
                        const userActivity = this.lifestyleAnswers.A;
                        let activityExplanation = '';
                        if (userActivity <= 2) {
                            activityExplanation = 'This breed has low energy needs and is happy with short walks or indoor play. Perfect for a relaxed lifestyle!';
                        } else if (userActivity <= 3) {
                            activityExplanation = 'This breed enjoys moderate exercise like daily walks and playtime, matching your activity level perfectly.';
                        } else {
                            activityExplanation = 'This breed is energetic and athletic, ready to join you on runs, hikes, and outdoor adventures!';
                        }
                        detailedReasons.push({
                            icon: '🏃',
                            title: 'Activity Level Match',
                            explanation: activityExplanation
                        });
                    }

                    // Check family friendliness
                    if (this.lifestyleAnswers.F >= 4 && breedProfile.F >= 4) {
                        detailedReasons.push({
                            icon: '👨‍👩‍👧‍👦',
                            title: 'Family-Friendly',
                            explanation: 'This breed is patient, gentle, and great with children and other family members. Safe and loving for all ages!'
                        });
                    }

                    // Check allergy compatibility
                    if (this.lifestyleAnswers.H === 1 && breedProfile.H >= 4) {
                        detailedReasons.push({
                            icon: '🌿',
                            title: 'Hypoallergenic Breed',
                            explanation: 'This breed is hypoallergenic with minimal shedding, making it safe and comfortable for people with allergies!'
                        });
                    }

                    // Check sociability
                    if (Math.abs(this.lifestyleAnswers.S - breedProfile.S) <= 1) {
                        const userSociability = this.lifestyleAnswers.S;
                        let socialExplanation = '';
                        if (userSociability <= 2) {
                            socialExplanation = 'This breed is independent and respects personal space while still being loving. Perfect if you prefer a calm companion!';
                        } else if (userSociability <= 3) {
                            socialExplanation = 'This breed has a balanced temperament - friendly when you want interaction, calm when you need space.';
                        } else {
                            socialExplanation = 'This breed is very affectionate and social, always ready to cuddle and follow you around. Your perfect shadow!';
                        }
                        detailedReasons.push({
                            icon: '💕',
                            title: 'Personality Match',
                            explanation: socialExplanation
                        });
                    }

                    // Check indoor/outdoor preference
                    const indoorDiff = Math.abs(this.lifestyleAnswers.I - breedProfile.I);
                    if (indoorDiff <= 1) {
                        const userIndoor = this.lifestyleAnswers.I;
                        let indoorExplanation = '';
                        if (userIndoor === 1) {
                            indoorExplanation = 'This breed thrives outdoors and can handle various weather conditions. Great for outdoor living!';
                        } else if (userIndoor === 5) {
                            indoorExplanation = 'This breed is perfectly suited for indoor living and will be comfortable staying inside with you.';
                        } else {
                            indoorExplanation = 'This breed is adaptable and happy with a mix of indoor comfort and outdoor time.';
                        }
                        detailedReasons.push({
                            icon: '🌤️',
                            title: 'Living Environment Match',
                            explanation: indoorExplanation
                        });
                    }

                    // Check experience level
                    if (this.lifestyleAnswers.E <= 2 && breedProfile.E <= 2) {
                        detailedReasons.push({
                            icon: '🌟',
                            title: 'Beginner-Friendly',
                            explanation: 'This breed is easy to train, forgiving of mistakes, and perfect for first-time pet owners. You\'ll learn together!'
                        });
                    } else if (this.lifestyleAnswers.E >= 4 && breedProfile.E >= 4) {
                        detailedReasons.push({
                            icon: '🎓',
                            title: 'Great For Experienced Owners',
                            explanation: 'This breed benefits from your experience and training knowledge. Together you\'ll achieve amazing results!'
                        });
                    }

                    // If no specific reasons, add helpful generic ones
                    if (detailedReasons.length === 0) {
                        detailedReasons.push({
                            icon: '✨',
                            title: 'Great Overall Match',
                            explanation: 'This breed has a balanced temperament and adaptable nature that works well with various lifestyles.'
                        });
                        detailedReasons.push({
                            icon: '🐾',
                            title: 'Wonderful Companion',
                            explanation: 'This breed is known for being loving, loyal, and a joy to have as part of your family.'
                        });
                    }

                    return detailedReasons.slice(0, 3); // Max 3 detailed reasons for readability
                },

                getBreedImage(breed) {
                    return breed.image || '/placeholder.svg?height=300&width=400';
                },

                async saveResults() {
                    const results = {
                        petType: this.petType,
                        preferences: this.lifestyleAnswers,
                        recommendedBreeds: this.recommendedBreeds.map(breed => ({
                            id: breed.id,
                            slug: breed.slug,
                            name: breed.name,
                            image: breed.image,
                            description: breed.description,
                            compatibility: breed.compatibility,
                            matchReasons: breed.detailedReasons?.map(r => r.title) || []
                        })),
                        lifestyleScores: this.lifestyleAnswers
                    };

                    try {
                        const res = await fetch('{{ route('assessment.save') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify(results)
                        });

                        let data = null;
                        try {
                            data = await res.json();
                        } catch (e) {
                            /* ignore JSON parse errors */ }

                        if (!res.ok) {
                            const msg = data && data.error ? data.error : (data && data.message ? data.message :
                                `Server error (${res.status})`);
                            console.error('Save failed:', msg, data);
                            alert('Error saving results: ' + msg);
                            return;
                        }

                        if (!data || !data.success) {
                            const msg = data && data.error ? data.error : 'Error saving results. Please try again.';
                            console.error('Save returned unsuccessful response', data);
                            alert(msg);
                            return;
                        }

                        this.resultsSaved = true;

                        // Show success notification
                        const notification = document.createElement('div');
                        notification.className =
                            'fixed bottom-8 right-8 bg-green-500 text-white px-6 py-4 rounded-xl shadow-xl z-50 animate-in';
                        notification.innerHTML = `
                            <div class="flex items-center gap-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div>
                                    <div class="font-semibold">Assessment Saved!</div>
                                    <div class="text-sm opacity-90">Your results are now saved</div>
                                </div>
                            </div>
                        `;
                        document.body.appendChild(notification);

                        setTimeout(() => notification.remove(), 3000);
                    } catch (error) {
                        console.error('Error saving results:', error);
                        alert('Error saving results. Please try again.');
                    }
                },

                restart() {
                    this.currentStage = 'intro';
                    this.petType = null;
                    this.currentQuestion = 0;
                    this.lifestyleAnswers = {};
                    this.attemptedNext = false;
                    this.recommendedBreeds = [];
                    this.resultsSaved = false;

                    window.scrollTo(0, 0);
                },
                truncateDescription(desc) {
                    if (!desc) return '';
                    const maxLength = window.innerWidth < 768 ? 100 : 120;
                    return desc.length > maxLength ? desc.slice(0, maxLength) + '...' : desc;
                },
            };
        }
    </script>
@endsection
