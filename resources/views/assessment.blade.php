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
            background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(255,250,251,0.98));
            border-radius: 18px;
            box-shadow: var(--shadow-lg);
            padding: 2rem;
            overflow-x: hidden;
            width: 100%;
            max-width: 100%;
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
            font-weight:700; 
            font-size:15px; 
            transition: all .22s ease; 
            box-shadow: 0 8px 28px rgba(255,99,127,0.12);
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,99,127,0.6);
        }
        
        .btn-secondary { 
            background: white; 
            color: var(--pet-primary); 
            border: 2px solid rgba(255,99,127,0.12); 
            padding: 10px 18px; 
            border-radius: 12px; 
            font-weight:600;
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
            border: 1px solid rgba(0,0,0,0.04); 
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
        
        .pet-card > * {
            position: relative;
            z-index: 1;
        }
        
        .progress-container { 
            background: rgba(255,99,127,0.06); 
            border-radius: 999px; 
            height: 10px; 
            overflow:hidden;
        }
        
        .progress-bar { 
            background: linear-gradient(90deg,var(--pet-primary),var(--pet-accent)); 
            height:100%; 
            border-radius:999px; 
            transition:width .45s ease;
            position: relative;
        }
        
        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        .radio-label { 
            position: relative; 
            cursor:pointer; 
            padding: 16px; 
            border-radius:12px; 
            transition:all .18s ease; 
            background:white;
            border: 2px solid #e5e7eb;
        }
        
        .radio-label:hover { 
            background: rgba(255,99,127,0.05);
            transform: translateY(-2px);
            border-color: var(--pet-primary);
        }
        
        .radio-label input[type="radio"] { 
            accent-color: var(--pet-primary);
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        
        .question-card { 
            background: linear-gradient(180deg, #fff 0%, #fffbfc 100%); 
            border-radius:14px; 
            padding: 24px; 
            margin-bottom: 24px; 
            box-shadow:0 6px 22px rgba(15,23,42,0.04);
            width: 100%;
            overflow: visible;
        }
        
        .question-card:hover {
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.12);
        }
        
        .question-text {
            font-size: 18px;
            line-height: 1.6;
            color: #1f2937;
            margin-bottom: 20px;
            font-weight: 600;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .breed-result-card { 
            background:white; 
            border-radius:14px; 
            overflow:hidden; 
            box-shadow:var(--shadow-lg); 
            transition:all .3s ease; 
            height:100%;
            display: flex;               /* added */
            flex-direction: column;      /* added */
        }
        
        .breed-result-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        /* Ensure content area grows and button sticks to bottom */
        .breed-result-card > .mt-5.p-6 {
            display: flex;               /* added */
            flex-direction: column;      /* added */
            flex: 1 1 auto;              /* added */
        }

        .breed-result-card .btn-gradient {
            margin-top: auto;            /* added - pushes button to the bottom */
            align-self: stretch;         /* added - makes button full width of card content */
        }
        
        .trait-badge {
            background: linear-gradient(135deg, var(--pet-primary) 0%, var(--pet-accent) 100%);
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin: 4px;
            transition: all 0.3s ease;
        }
        
        .trait-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255,99,127,0.4);
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
            display:inline-block;
        }
        
        .pet-card:hover .emoji-icon {
            transform: scale(1.2) rotate(5deg);
        }
        
        .section-badge { 
            background: linear-gradient(90deg,#ffdfe6,#fff1f3); 
            color:var(--pet-primary); 
            padding:8px 16px; 
            border-radius:999px; 
            font-weight:700;
            display: inline-block;
        }
        
        .hair-option-card { 
            position: relative; 
            overflow:hidden; 
            border-radius:12px; 
            cursor:pointer;
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
            border: 2px solid rgba(255,99,127,0.08); 
            padding:10px 18px; 
            border-radius:10px; 
            font-weight:700;
        }
        
        .back-button:hover { 
            transform: translateX(-4px); 
            border-color: rgba(255,99,127,0.18);
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

        /* Responsive tweaks */
        @media (max-width: 768px) {
            .quiz-container {
                padding: 1.25rem;
                margin: 0 0.5rem;
            }
            .gradient-text {
                font-size: 2.2rem !important;
                line-height: 1.1;
                word-break: break-word;
                white-space: normal;
            }
            .quiz-container h1 {
                font-size: 2.2rem !important;
                line-height: 1.1;
                word-break: break-word;
                white-space: normal;
            }
            .btn-gradient {
                font-size: 1rem;
                padding: 12px 0;
                width: 100%;
                min-width: 0;
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
                        <p class="text-xl text-gray-600">Discover your perfect pet companion through our veterinary-verified assessment</p>
                    </div>
                    
                    <div class="flex flex-col items-center gap-4 mt-12">
                        <button class="btn-gradient w-full max-w-md" @click="currentStage = 'petType'">
                            <span class="text-lg">Start Your Journey</span>
                        </button>
                        
                        @auth
                            @if(auth()->user()->assessments->count() > 0)
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
                <div class="quiz-container p-8 md:p-12 animate-in">
                    <div class="mb-8">
                        <span class="section-badge">Step 2 of 2</span>
                        <h2 class="text-3xl md:text-4xl font-bold gradient-text mt-4">Lifestyle Assessment</h2>
                        <p class="text-gray-600 mt-2">
                            Question <span x-text="currentQuestion + 1"></span> of <span x-text="lifestyleQuestions.length"></span>
                        </p>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-8">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Overall Progress</span>
                            <span x-text="Math.round(((currentQuestion + 1) / lifestyleQuestions.length) * 100) + '%'"></span>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar" :style="`width: ${Math.round(((currentQuestion + 1) / lifestyleQuestions.length) * 100)}%`"></div>
                        </div>
                    </div>

                    <!-- Current Question -->
                    <div class="question-card">
                        <h3 class="question-text" x-text="lifestyleQuestions[currentQuestion].question"></h3>
                        
                        <div class="space-y-3">
                            <template x-for="option in lifestyleQuestions[currentQuestion].options" :key="option.value">
                                <label class="radio-label cursor-pointer flex items-center transition-all"
                                       :class="lifestyleAnswers[lifestyleQuestions[currentQuestion].code] === option.value ? 'border-purple-500 bg-purple-50 shadow-md' : 'border-gray-200 hover:border-purple-300'">
                                    <input type="radio" 
                                           :name="'q' + currentQuestion" 
                                           :value="option.value"
                                           x-model="lifestyleAnswers[lifestyleQuestions[currentQuestion].code]"
                                           class="mr-3">
                                    <span class="text-base font-medium" x-text="option.label"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- Warning Message -->
                    <div class="mt-6" x-show="attemptedNext && !lifestyleAnswers[lifestyleQuestions[currentQuestion].code]">
                        <div class="bg-amber-100 border-l-4 border-amber-500 p-4 rounded-lg">
                            <p class="text-amber-800 font-semibold">
                                ⚠️ Please select an answer before continuing.
                            </p>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="mt-8 flex flex-col md:flex-row justify-between items-center gap-4">
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
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                        <template x-for="(breed, index) in recommendedBreeds" :key="index">
                            <div class="mt-2 breed-result-card">
                                <div class="overflow-hidden">
                                    <img :src="getBreedImage(breed)" :alt="breed.name" class="breed-image" loading="lazy">
                                </div>
                                <div class="mt-5 p-6">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-2xl font-bold text-gray-800" x-text="breed.name"></h3>
                                        <span class="compatibility-badge" x-text="breed.compatibility + '%'"></span>
                                    </div>
                                    <p class="text-gray-600 text-sm mb-4" x-text="truncateDescription(breed.description)"></p>
                                    
                                    <div class="mb-4">
                                        <div class="text-sm font-semibold text-gray-700 mb-2">Why This Match:</div>
                                        <div class="flex flex-wrap gap-2">
                                            <template x-for="(reason, i) in breed.matchReasons" :key="i">
                                                <span class="trait-badge" x-text="reason"></span>
                                            </template>
                                        </div>
                                    </div>
                                    
                                    <a :href="'/' + petType + 's/' + breed.slug" 
                                       class="btn-gradient w-full block text-center">
                                       Learn More
                                    </a>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="text-center space-y-4">
                        @auth
                            <button x-show="!resultsSaved" 
                                    class="mt-3 btn-gradient" 
                                    @click="saveResults">
                                💾 Save My Results
                            </button>
                            
                            <div x-show="resultsSaved" 
                                 class="mt-3 inline-flex items-center gap-2 bg-green-500 text-white px-8 py-4 rounded-xl font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
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
                
                // Factor weights for distance calculation
                weights: {
                    T: 0.12, // Time
                    L: 0.12, // Living Space
                    A: 0.12, // Activity
                    F: 0.10, // Family
                    E: 0.10, // Experience
                    G: 0.10, // Grooming
                    H: 0.10, // Allergy
                    S: 0.08, // Sociability
                    P: 0.08, // Purpose
                    I: 0.08, // Indoor/Outdoor
                    B: 0.10  // Budget
                },
                
                lifestyleQuestions: [
                    { 
                        question: "How many hours a day can you spend with your pet?", 
                        code: "T",
                        options: [
                            { label: "Less than 2 hours", value: 1 },
                            { label: "2-3 hours", value: 2 },
                            { label: "4-6 hours", value: 3 },
                            { label: "6-8 hours", value: 4 },
                            { label: "Almost all day", value: 5 }
                        ]
                    },
                    { 
                        question: "What type of home do you live in?", 
                        code: "L",
                        options: [
                            { label: "Small apartment", value: 1 },
                            { label: "Condo", value: 2 },
                            { label: "Townhouse", value: 3 },
                            { label: "House with yard", value: 4 },
                            { label: "Farm/open area", value: 5 }
                        ]
                    },
                    { 
                        question: "How active are you daily?", 
                        code: "A",
                        options: [
                            { label: "Sedentary (minimal movement)", value: 1 },
                            { label: "Light walks occasionally", value: 2 },
                            { label: "Moderate activity", value: 3 },
                            { label: "Exercise regularly", value: 4 },
                            { label: "Very active/outdoorsy", value: 5 }
                        ]
                    },
                    { 
                        question: "Do you have children, elderly, or other pets at home?", 
                        code: "F",
                        options: [
                            { label: "None", value: 2 },
                            { label: "Elderly members", value: 3 },
                            { label: "Children and elderly", value: 4 },
                            { label: "Young children", value: 5 }
                        ]
                    },
                    { 
                        question: "Have you owned pets before?", 
                        code: "E",
                        options: [
                            { label: "No experience", value: 1 },
                            { label: "Beginner", value: 2 },
                            { label: "Some experience", value: 3 },
                            { label: "Experienced", value: 4 },
                            { label: "Expert/Professional", value: 5 }
                        ]
                    },
                    { 
                        question: "How much time can you spend grooming your pet?", 
                        code: "G",
                        options: [
                            { label: "None/Self-grooming", value: 0 },
                            { label: "Minimal (as needed)", value: 1 },
                            { label: "Once a week", value: 2 },
                            { label: "Twice a week", value: 3 },
                            { label: "Every other day", value: 4 },
                            { label: "Daily/enjoy grooming", value: 5 }
                        ]
                    },
                    { 
                        question: "Do you or anyone in your household have pet hair allergies?", 
                        code: "H",
                        options: [
                            { label: "Yes, we have allergies", value: 1 },
                            { label: "No allergies", value: 5 }
                        ]
                    },
                    { 
                        question: "Do you prefer a clingy or independent pet?", 
                        code: "S",
                        options: [
                            { label: "Very independent", value: 1 },
                            { label: "Slightly social", value: 2 },
                            { label: "Moderate", value: 3 },
                            { label: "Friendly and social", value: 4 },
                            { label: "Very affectionate/clingy", value: 5 }
                        ]
                    },
                    { 
                        question: "What's your main reason for owning a pet?", 
                        code: "P",
                        options: [
                            { label: "Guard/Protection", value: 1 },
                            { label: "Companion", value: 2 },
                            { label: "Exercise partner", value: 3 },
                            { label: "Emotional comfort", value: 4 },
                            { label: "Show/Breeding", value: 5 }
                        ]
                    },
                    { 
                        question: "Will your pet mostly stay indoors or outdoors?", 
                        code: "I",
                        options: [
                            { label: "Mostly outdoors", value: 1 },
                            { label: "Mix of both", value: 3 },
                            { label: "Mostly indoors", value: 5 }
                        ]
                    },
                    { 
                        question: "What is your monthly pet budget (PHP)?", 
                        code: "B",
                        options: [
                            { label: "Less than ₱2,000", value: 1 },
                            { label: "₱2,000 - ₱4,999", value: 2 },
                            { label: "₱5,000 - ₱9,999", value: 3 },
                            { label: "₱10,000 - ₱14,999", value: 4 },
                            { label: "₱15,000 or more", value: 5 }
                        ]
                    }
                ],
                
                breedProfiles: {
                    dog: {
                        // Local breed - outdoor, no grooming
                        'Aspin': { T:3, L:3, A:3, F:5, E:1, G:0, H:5, S:4, P:2, I:1, B:1 },
                        
                        // Small indoor breeds - high grooming
                        'Shih Tzu': { T:4, L:2, A:2, F:5, E:2, G:5, H:3, S:5, P:2, I:5, B:3 },
                        'Pomeranian': { T:3, L:2, A:3, F:4, E:2, G:5, H:1, S:5, P:3, I:5, B:3 },
                        'Japanese Spitz': { T:3, L:3, A:3, F:5, E:2, G:4, H:2, S:5, P:2, I:5, B:3 },
                        
                        // Small indoor breeds - low grooming
                        'Pug': { T:3, L:2, A:2, F:5, E:1, G:1, H:4, S:5, P:2, I:5, B:2 },
                        'Chihuahua': { T:3, L:2, A:3, F:4, E:2, G:1, H:4, S:4, P:2, I:5, B:2 },
                        'French Bulldog': { T:3, L:2, A:2, F:5, E:2, G:1, H:4, S:5, P:2, I:5, B:4 },
                        'Boston Terrier': { T:3, L:2, A:3, F:5, E:2, G:1, H:4, S:4, P:2, I:5, B:3 },
                        'Dachshund': { T:3, L:3, A:3, F:4, E:2, G:1, H:4, S:4, P:2, I:5, B:3 },
                        
                        // Medium breeds - moderate grooming
                        'Beagle': { T:3, L:3, A:4, F:5, E:2, G:1, H:4, S:4, P:3, I:3, B:2 },
                        'Poodle': { T:4, L:2, A:3, F:5, E:3, G:5, H:5, S:5, P:3, I:5, B:3 },
                        
                        // Large active breeds - can do both indoor/outdoor
                        'Labrador Retriever': { T:4, L:4, A:4, F:5, E:3, G:2, H:4, S:5, P:3, I:3, B:4 },
                        'Boxer': { T:4, L:4, A:4, F:4, E:3, G:1, H:3, S:4, P:1, I:3, B:3 },
                        'German Shepherd': { T:4, L:5, A:5, F:4, E:4, G:3, H:3, S:3, P:1, I:3, B:4 },
                        'Dobermann': { T:4, L:5, A:5, F:3, E:4, G:1, H:3, S:2, P:1, I:3, B:4 },
                        'Bullmastiff': { T:4, L:5, A:3, F:4, E:4, G:1, H:3, S:2, P:1, I:3, B:4 },
                        
                        // Working/outdoor breeds - prefer outdoor
                        'Siberian Husky': { T:4, L:5, A:5, F:4, E:4, G:3, H:2, S:4, P:3, I:1, B:4 },
                        'Alaskan Malamute': { T:4, L:5, A:5, F:4, E:4, G:4, H:2, S:3, P:3, I:1, B:5 },
                        'Akita': { T:4, L:5, A:4, F:3, E:4, G:4, H:2, S:3, P:1, I:3, B:5 },
                        
                        // Special grooming needs
                        'Chow Chow': { T:3, L:4, A:2, F:4, E:3, G:5, H:1, S:2, P:4, I:3, B:4 }
                    },
                    cat: {
                        // Local breed - outdoor, no grooming
                        'Puspin': { T:2, L:2, A:3, F:5, E:1, G:0, H:5, S:3, P:2, I:1, B:1 },
                        
                        // Indoor cats - low grooming (short hair)
                        'Siamese': { T:3, L:3, A:3, F:4, E:2, G:1, H:3, S:5, P:3, I:5, B:3 },
                        'Russian Blue': { T:3, L:3, A:2, F:4, E:3, G:1, H:5, S:4, P:3, I:5, B:3 },
                        'American Shorthair': { T:3, L:3, A:3, F:5, E:2, G:1, H:4, S:4, P:3, I:5, B:2 },
                        'British Shorthair': { T:3, L:3, A:2, F:4, E:2, G:1, H:4, S:3, P:3, I:5, B:3 },
                        'Exotic Shorthair': { T:3, L:3, A:2, F:4, E:2, G:2, H:4, S:4, P:3, I:5, B:3 },
                        
                        // Indoor cats - high grooming (long hair)
                        'Persian': { T:4, L:3, A:1, F:4, E:2, G:5, H:1, S:5, P:4, I:5, B:4 },
                        'Himalayan': { T:4, L:3, A:1, F:4, E:2, G:5, H:1, S:5, P:4, I:5, B:4 },
                        
                        // Large cat - can do both
                        'Maine Coon': { T:4, L:5, A:4, F:5, E:4, G:4, H:2, S:5, P:3, I:3, B:5 }
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
                    
                    // Apply hard filters
                    const breeds = this.breedProfiles[this.petType];
                    let candidates = Object.keys(breeds);
                    
                    // Filter 1: Allergy (H) - if allergic, need hypoallergenic breeds
                    if (this.lifestyleAnswers.H === 1) {
                        candidates = candidates.filter(name => breeds[name].H >= 4);
                        console.log('After allergy filter:', candidates.length);
                    }
                    
                    // Filter 2: Living Space (L) - small spaces can't have large breeds
                    if (this.lifestyleAnswers.L <= 2) {
                        candidates = candidates.filter(name => breeds[name].L <= 3);
                        console.log('After living space filter:', candidates.length);
                    }
                    
                    // Filter 3: Time (T) - low time can't have high-maintenance breeds
                    if (this.lifestyleAnswers.T <= 2) {
                        candidates = candidates.filter(name => breeds[name].T <= 3);
                        console.log('After time filter:', candidates.length);
                    }
                    
                    // Filter 4: Budget (B) - low budget can't afford premium breeds
                    if (this.lifestyleAnswers.B <= 2) {
                        candidates = candidates.filter(name => breeds[name].B <= 3);
                        console.log('After budget filter:', candidates.length);
                    }
                    
                    if (candidates.length === 0) {
                        console.warn('No breeds passed filters - relaxing constraints');
                        candidates = Object.keys(breeds);
                    }
                    
                    // Calculate weighted Euclidean distance for each candidate
                    const scored = candidates.map(name => {
                        const breed = breeds[name];
                        let sumSquaredDiff = 0;
                        
                        ['T', 'L', 'A', 'F', 'E', 'G', 'H', 'S', 'P', 'I', 'B'].forEach(factor => {
                            const userVal = this.lifestyleAnswers[factor] || 3;
                            const breedVal = breed[factor];
                            const diff = userVal - breedVal;
                            sumSquaredDiff += this.weights[factor] * (diff * diff);
                        });
                        
                        const distance = Math.sqrt(sumSquaredDiff);
                        const compatibility = Math.max(0, Math.min(100, 100 - (distance * 10)));
                        
                        return {
                            name: name,
                            distance: distance,
                            compatibility: Math.round(compatibility),
                            profile: breed
                        };
                    });
                    
                    // Sort by distance (lower = better match)
                    scored.sort((a, b) => a.distance - b.distance);
                    
                    console.log('Top 5 matches:', scored.slice(0, 5));
                    
                    // Get top 3 matches
                    const top3 = scored.slice(0, 3);
                    
                    // Get breed data from backend
                    let masterBreeds = [];
                    try {
                        if (this.petType === 'dog') {
                            masterBreeds = JSON.parse('{!! addslashes($dogBreeds ?? "[]") !!}');
                        } else {
                            masterBreeds = JSON.parse('{!! addslashes($catBreeds ?? "[]") !!}');
                        }
                    } catch (e) {
                        console.error('Error loading breed data:', e);
                    }
                    
                    // Map to full breed info
                    this.recommendedBreeds = top3.map(match => {
                        const breedData = masterBreeds.find(b => 
                            b.name.toLowerCase() === match.name.toLowerCase()
                        );
                        
                        // Generate match reasons
                        const reasons = this.generateMatchReasons(match.profile);
                        
                        return {
                            name: match.name,
                            slug: breedData?.slug || match.name.toLowerCase().replace(/\s+/g, '-'),
                            image: breedData?.image || '/placeholder.svg?height=300&width=400',
                            description: breedData?.description || 'A wonderful companion for you.',
                            compatibility: match.compatibility,
                            matchReasons: reasons,
                            distance: match.distance
                        };
                    });
                    
                    console.log('Final recommendations:', this.recommendedBreeds);
                },
                
                generateMatchReasons(breedProfile) {
                    const reasons = [];
                    
                    // Check time commitment
                    if (Math.abs(this.lifestyleAnswers.T - breedProfile.T) <= 1) {
                        reasons.push('Time commitment match');
                    }
                    
                    // Check living space
                    if (Math.abs(this.lifestyleAnswers.L - breedProfile.L) <= 1) {
                        reasons.push('Perfect for your home');
                    }
                    
                    // Check activity level
                    if (Math.abs(this.lifestyleAnswers.A - breedProfile.A) <= 1) {
                        reasons.push('Activity level match');
                    }
                    
                    // Check family friendliness
                    if (this.lifestyleAnswers.F >= 4 && breedProfile.F >= 4) {
                        reasons.push('Family-friendly');
                    }
                    
                    // Check grooming - handle 0-5 scale
                    const groomingDiff = Math.abs(this.lifestyleAnswers.G - breedProfile.G);
                    if (groomingDiff <= 1) {
                        if (this.lifestyleAnswers.G === 0 && breedProfile.G === 0) {
                            reasons.push('No grooming needed');
                        } else if (this.lifestyleAnswers.G <= 1 && breedProfile.G <= 1) {
                            reasons.push('Low maintenance');
                        } else {
                            reasons.push('Grooming fits lifestyle');
                        }
                    }
                    
                    // Check allergy compatibility
                    if (this.lifestyleAnswers.H === 1 && breedProfile.H >= 4) {
                        reasons.push('Hypoallergenic');
                    }
                    
                    // Check sociability
                    if (Math.abs(this.lifestyleAnswers.S - breedProfile.S) <= 1) {
                        reasons.push('Personality match');
                    }
                    
                    // Check indoor/outdoor preference
                    const indoorDiff = Math.abs(this.lifestyleAnswers.I - breedProfile.I);
                    if (indoorDiff <= 1) {
                        if (this.lifestyleAnswers.I === 1 && breedProfile.I === 1) {
                            reasons.push('Outdoor-adapted');
                        } else if (this.lifestyleAnswers.I === 5 && breedProfile.I === 5) {
                            reasons.push('Indoor-friendly');
                        } else {
                            reasons.push('Living style match');
                        }
                    }
                    
                    // Check budget
                    if (Math.abs(this.lifestyleAnswers.B - breedProfile.B) <= 1) {
                        if (this.lifestyleAnswers.B <= 2 && breedProfile.B <= 2) {
                            reasons.push('Very affordable');
                        } else {
                            reasons.push('Budget-friendly');
                        }
                    }
                    
                    // Check experience level
                    if (this.lifestyleAnswers.E <= 2 && breedProfile.E <= 2) {
                        reasons.push('Beginner-friendly');
                    } else if (this.lifestyleAnswers.E >= 4 && breedProfile.E >= 4) {
                        reasons.push('Expert-level breed');
                    }
                    
                    // If no specific reasons, add generic ones
                    if (reasons.length === 0) {
                        reasons.push('Good overall match', 'Balanced temperament');
                    }
                    
                    return reasons.slice(0, 4); // Max 4 reasons
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
                            matchReasons: breed.matchReasons
                        })),
                        lifestyleScores: this.lifestyleAnswers
                    };
                    
                    try {
                        const res = await fetch('{{ route('assessment.save') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(results)
                        });

                        let data = null;
                        try { data = await res.json(); } catch(e) { /* ignore JSON parse errors */ }

                        if (!res.ok) {
                            const msg = data && data.error ? data.error : (data && data.message ? data.message : `Server error (${res.status})`);
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
                        notification.className = 'fixed bottom-8 right-8 bg-green-500 text-white px-6 py-4 rounded-xl shadow-xl z-50 animate-in';
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
                    return desc.length > 120 ? desc.slice(0, 120) + '...' : desc;
                },
            };
        }
    </script>
@endsection