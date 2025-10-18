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
            --pet-primary: #ff637f; /* main accent */
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
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
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
            background: #667eea;
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
            background: var(--gradient-primary);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 0;
        }
        
        .pet-card:hover::before {
            opacity: 0.1;
        }
        
        .pet-card:hover {
            transform: translateY(-10px) scale(1.02);
            border-color: #667eea;
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
            padding: 10px 8px; 
            border-radius:12px; 
            transition:all .18s ease; 
            background:transparent;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 60px;
        }
        
        .radio-label:hover { 
            background: rgba(255,99,127,0.05);
            transform: translateY(-2px);
        }
        
        .radio-label input[type="radio"] { 
            accent-color: var(--pet-primary);
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        
        .radio-label input[type="radio"]:focus, 
        .radio-label input[type="radio"]:hover {
            outline: none;
            box-shadow: none;
            scroll-margin: 0 !important;
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
            font-size: 16px;
            line-height: 1.6;
            color: #1f2937;
            margin-bottom: 20px;
            font-weight: 500;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .radio-options-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .radio-options {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            min-width: 100%;
        }
        
        .scale-labels {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 12px;
            color: var(--pet-muted);
            font-weight: 500;
        }
        
        .breed-result-card { 
            background:white; 
            border-radius:14px; 
            overflow:hidden; 
            box-shadow:var(--shadow-lg); 
            transition:all .3s ease; 
            height:100%;
        }
        
        .breed-result-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }
        
        .breed-image {
            width: 100%;
            height: 240px;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        
        .breed-result-card:hover .breed-image {
            transform: scale(1.1);
        }
        
        .trait-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .flash-message {
            background: var(--gradient-success);
            color: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(67, 233, 123, 0.3);
            animation: slideInDown 0.5s ease;
        }
        
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
        
        .notification-toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--gradient-success);
            color: white;
            padding: 20px 30px;
            border-radius: 16px;
            box-shadow: var(--shadow-xl);
            z-index: 9999;
            animation: slideInRight 0.5s ease;
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideOutRight {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100px);
            }
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

        /* Responsive tweaks */
        @media (max-width: 768px) {
            .quiz-container { 
                padding: 1.25rem;
                margin: 0 0.5rem;
            }
            
            .pet-card { 
                padding: 20px;
            }
            
            .question-card { 
                padding: 16px;
                margin-bottom: 16px;
            }
            
            .question-text {
                font-size: 15px;
                margin-bottom: 16px;
            }
            
            .emoji-icon { 
                font-size: 48px;
            }
            
            .btn-gradient, .btn-secondary { 
                padding: 10px 16px;
                font-size: 14px;
            }
            
            .radio-label {
                padding: 8px 4px;
                min-height: 50px;
            }
            
            .radio-label input[type="radio"] {
                width: 18px;
                height: 18px;
            }
            
            .scale-labels {
                font-size: 10px;
            }
            
            .radio-options {
                gap: 4px;
            }
        }
        
        @media (max-width: 480px) {
            .quiz-container {
                padding: 1rem;
                border-radius: 12px;
            }
            
            .question-card {
                padding: 14px;
            }
            
            .question-text {
                font-size: 14px;
            }
            
            .radio-label {
                padding: 6px 2px;
                min-height: 45px;
            }
            
            .radio-label input[type="radio"] {
                width: 16px;
                height: 16px;
            }
            
            .scale-labels {
                font-size: 9px;
            }
        }
    </style>
    
    <div class="min-h-screen py-12 px-4" x-data="quizApp()">
        
        @if(Session::has('assessment_saved'))
        <div class="max-w-3xl mx-auto mb-6">
            <div class="flash-message" x-data="{ show: true }" x-init="setTimeout(() => { show = false }, 5000)" x-show="show" x-transition>
                <div class="flex items-center gap-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <div>
                        <h3 class="font-bold text-xl">Assessment Saved!</h3>
                        <p class="opacity-90">Your assessment results have been successfully saved.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <div class="container max-w-5xl mx-auto">
            
            <!-- Introduction -->
            <template x-if="currentStage === 'intro'">
                <div class="quiz-container p-12 text-center animate-in">
                    <div class="mb-8">
                        <h1 class="text-6xl font-bold gradient-text mb-4">Pet Personality Matcher</h1>
                        <p class="text-xl text-gray-600">Discover your perfect pet companion through our advanced personality assessment</p>
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
                        <span class="section-badge">Step 1 of 4</span>
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

            <!-- Step 2: Choose hair length -->
            <template x-if="currentStage === 'hairLength'">
                <div class="quiz-container p-12 animate-in">
                    <div class="text-center mb-12">
                        <span class="section-badge">Step 2 of 4</span>
                        <h2 class="text-4xl font-bold gradient-text mt-4">Select Hair Length</h2>
                        <p class="text-gray-600 mt-2">What grooming commitment fits your lifestyle?</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="mt-5 hair-option-card bg-white border-4 border-transparent hover:border-purple-500" 
                             @click="preferences.hairLength = 'short'; currentStage = 'size'">
                            <div class="overflow-hidden rounded-t-lg">
                                <img :src="petType === 'dog' ? 'https://topdogtips.com/wp-content/uploads/2017/04/Best-short-hair-dog-breeds-16.jpg' : 'https://www.petrescueblog.com/wp-content/uploads/2021/01/e8901c74e0ffaebaac19d375c30c39b8-1140x855.jpg'" 
                                    alt="Short hair" class="w-full h-64 object-cover transition-transform duration-500 hover:scale-110">
                            </div>
                            <div class="p-6">
                                <h3 class="text-2xl font-bold text-gray-800">Short Hair</h3>
                                <p class="text-gray-600 mt-2">Low maintenance, easy grooming</p>
                            </div>
                        </div>
                        
                        <div class="mt-5 hair-option-card bg-white border-4 border-transparent hover:border-purple-500" 
                             @click="preferences.hairLength = 'long'; currentStage = 'size'">
                            <div class="overflow-hidden rounded-t-lg">
                                <img :src="petType === 'dog' ? 'https://tse1.mm.bing.net/th/id/OIP.oj0himbKqq-E9Qz_x8EYrwHaHa?cb=12&rs=1&pid=ImgDetMain&o=7&rm=3' : 'https://static9.depositphotos.com/1594920/1089/i/950/depositphotos_10893465-stock-photo-british-longhair-kitten-3-months.jpg'" 
                                    alt="Long hair" class="w-full h-64 object-cover transition-transform duration-500 hover:scale-110">
                            </div>
                            <div class="p-6">
                                <h3 class="text-2xl font-bold text-gray-800">Long Hair</h3>
                                <p class="text-gray-600 mt-2">Luxurious coat, regular grooming needed</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <button class="back-button" @click="currentStage = 'petType'">
                            ← Back
                        </button>
                    </div>
                </div>
            </template>

            <!-- Step 3: Choose size -->
            <template x-if="currentStage === 'size'">
                <div class="quiz-container p-12 animate-in">
                    <div class="text-center mb-12">
                        <span class="section-badge">Step 3 of 4</span>
                        <h2 class="text-4xl font-bold gradient-text mt-4">Choose Your Size</h2>
                        <p class="text-gray-600 mt-2">What size pet fits your living space?</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="mt-5 pet-card" @click="preferences.size = 'small'; currentStage = 'personality'">
                            <span class="emoji-icon size-option text-5xl">🐶🐱</span>
                            <h3 class="text-2xl font-bold mt-4 text-gray-800">Small</h3>
                            <p class="text-gray-600 mt-2">Perfect for apartments</p>
                        </div>
                        
                        <div class="mt-5 pet-card" @click="preferences.size = 'medium'; currentStage = 'personality'">
                            <span class="emoji-icon size-option text-6xl">🐕🐈</span>
                            <h3 class="text-2xl font-bold mt-4 text-gray-800">Medium</h3>
                            <p class="text-gray-600 mt-2">Balanced and versatile</p>
                        </div>
                        
                        <div class="mt-5 pet-card" @click="preferences.size = 'large'; currentStage = 'personality'">
                            <span class="emoji-icon size-option text-7xl">🐕🐕‍🦺🐈‍⬛</span>
                            <h3 class="text-2xl font-bold mt-4 text-gray-800">Large</h3>
                            <p class="text-gray-600 mt-2">Needs spacious home</p>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <button class="back-button" @click="currentStage = 'hairLength'">
                            ← Back
                        </button>
                    </div>
                </div>
            </template>

            <!-- Step 4: Personality Questions -->
            <template x-if="currentStage === 'personality'">
                <div class="quiz-container p-8 md:p-12 animate-in">
                    <div class="mb-8">
                        <span class="section-badge">Step 4 of 4</span>
                        <h2 class="text-3xl md:text-4xl font-bold gradient-text mt-4">Personality Assessment</h2>
                        <p class="text-gray-600 mt-2">
                            Set <span x-text="currentSection + 1"></span> of <span x-text="totalSections"></span> · 
                            Questions <span x-text="(currentSection*questionsPerSection)+1"></span>-<span x-text="Math.min((currentSection+1)*questionsPerSection, personalityQuestions.length)"></span>
                        </p>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-8">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Overall Progress</span>
                            <span x-text="Math.round((answeredCount()/personalityQuestions.length)*100) + '%'"></span>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar" :style="`width: ${Math.round((answeredCount()/personalityQuestions.length)*100)}%`"></div>
                        </div>
                    </div>

                    <!-- Questions -->
                    <div class="space-y-6">
                        <template x-for="(q, i) in sectionQuestions()" :key="currentSection + '-' + i">
                            <div class="question-card">
                                <h3 class="question-text" x-text="q.question"></h3>
                                
                                <div class="scale-labels">
                                    <span>Strongly Disagree</span>
                                    <span>Strongly Agree</span>
                                </div>
                                
                                <div class="radio-options-container">
                                    <div class="radio-options">
                                        <template x-for="n in 5" :key="n">
                                            <label class="radio-label flex-1">
                                                <input type="radio" 
                                                       :name="'q'+(sectionBaseIndex()+i)" 
                                                       :value="n"
                                                       x-model="personalityAnswers[sectionBaseIndex()+i]">
                                                <span class="text-sm font-semibold mt-2" x-text="n"></span>
                                            </label>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Warning Message -->
                    <div class="mt-6" x-show="attemptedNext && !isSectionComplete()">
                        <div class="bg-amber-100 border-l-4 border-amber-500 p-4 rounded-lg">
                            <p class="text-amber-800 font-semibold">
                                ⚠️ Please answer all questions in this set before continuing.
                            </p>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="mt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex gap-3 flex-wrap">
                            <button class="back-button" @click="prevSet" x-show="currentSection > 0">
                                ← Back
                            </button>
                            <button class="back-button" @click="currentStage = 'size'">
                                Back to Preferences
                            </button>
                        </div>
                        
                        <div class="flex items-center gap-4 flex-wrap justify-center">
                            <span class="text-sm text-gray-600">
                                Progress: <span x-text="sectionAnsweredCount()"></span>/<span x-text="questionsPerSection"></span>
                            </span>
                            <button class="btn-gradient" @click="isSectionComplete() ? nextSet() : attemptedNext = true">
                                <span x-show="currentSection < totalSections - 1">Next Set →</span>
                                <span x-show="currentSection === totalSections - 1">See Results ✨</span>
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Results -->
            <template x-if="currentStage === 'results'">
                <div class="quiz-container p-8 md:p-12 animate-in">
                    <div class="text-center mb-12">
                        <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-4">Your Perfect Matches! 🎉</h2>
                        <p class="text-xl text-gray-600">Based on your unique personality, here are your ideal companions</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                        <template x-for="(breed, index) in recommendedBreeds" :key="index">
                            <div class="mt-2 breed-result-card">
                                <div class="overflow-hidden">
                                    <img :src="getBreedImage(breed)" :alt="breed.name" class="breed-image" loading="lazy">
                                </div>
                                <div class="mt-5 p-6">
                                    <h3 class="text-2xl font-bold text-gray-800 mb-2" x-text="breed.name"></h3>
                                    
                                    <p class="text-gray-600 text-sm mb-4" x-text="breed.description.length > 100 ? breed.description.substring(0, 100) + '...' : breed.description"></p>
                                    
                                    <div class="mb-4">
                                        <div class="text-sm font-semibold text-gray-700 mb-2">Your Personality Traits:</div>
                                        <div class="flex flex-wrap gap-2">
                                            <template x-for="(trait, i) in breed.traits" :key="i">
                                                <span class="trait-badge" x-text="trait"></span>
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
                            <button x-show="!resultsSaved && !hasSavedResults" 
                                    class="mt-3 btn-gradient" 
                                    @click="saveResults">
                                💾 Save My Results
                            </button>
                            
                            <div x-show="resultsSaved && !hasSavedResults" 
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
                    
                    <div class="mt-8 p-6 bg-gradient-to-r from-purple-100 to-pink-100 rounded-2xl" 
                         x-show="resultsSaved && !hasSavedResults"
                         x-transition>
                        <h4 class="font-bold text-lg text-gray-800 mb-2">🎊 Success!</h4>
                        <p class="text-gray-700">Your perfect pet matches have been saved. You can access them anytime!</p>
                    </div>
                </div>
            </template>

        </div>
    </div>

    <!-- Alpine.js Logic -->
    <script>
        function quizApp() {
    // Safe initialization with try/catch
    let savedResults = { petType: null, preferences: null, recommendedBreeds: [] };
    let hasSavedResults = false;
    let masterDogBreeds = [];
    let masterCatBreeds = [];
    
    try { 
        masterDogBreeds = JSON.parse('{!! addslashes($dogBreeds ?? "[]") !!}'); 
    } catch (e) { 
        console.error('Error loading dog breeds:', e);
        masterDogBreeds = []; 
    }
    
    try { 
        masterCatBreeds = JSON.parse('{!! addslashes($catBreeds ?? "[]") !!}'); 
    } catch (e) { 
        console.error('Error loading cat breeds:', e);
        masterCatBreeds = []; 
    }
    
    try {
        if (typeof '{!! addslashes($savedResults) !!}' === 'string') {
            const savedResultsJson = '{!! addslashes($savedResults) !!}';
            savedResults = JSON.parse(savedResultsJson);
            hasSavedResults = savedResults && 
                              savedResults.petType !== null && 
                              savedResults.preferences && 
                              savedResults.recommendedBreeds && 
                              savedResults.recommendedBreeds.length > 0;
            
            const urlParams = new URLSearchParams(window.location.search);
            const resetParam = urlParams.get('reset');
            
            if (resetParam === 'true') {
                hasSavedResults = false;
                savedResults = { petType: null, preferences: null, recommendedBreeds: [] };
                window.history.replaceState({}, document.title, window.location.pathname);
            }
            
            if (hasSavedResults) {
                console.log('Loaded saved results:', {
                    petType: savedResults.petType,
                    preferences: savedResults.preferences,
                    breedCount: savedResults.recommendedBreeds.length
                });
            }
        }
    } catch (e) {
        console.error('Error parsing saved results:', e);
    }
    
    const isValidPetType = (type) => type === 'dog' || type === 'cat';
    const hasValidSavedResults = hasSavedResults && 
        isValidPetType(savedResults.petType) && 
        savedResults.recommendedBreeds && 
        savedResults.recommendedBreeds.length > 0;
    
    const urlParams = new URLSearchParams(window.location.search);
    const requestedStart = urlParams.get('start');

    return {
        currentStage: hasValidSavedResults ? 'results' : (requestedStart ? requestedStart : 'intro'),
        petType: hasValidSavedResults ? savedResults.petType : null,
        preferences: hasSavedResults ? savedResults.preferences : {
            hairLength: null,
            size: null,
        },
        
        masterDogBreeds: masterDogBreeds,
        masterCatBreeds: masterCatBreeds,
        dogSlugIndex: Object.fromEntries((masterDogBreeds || []).map(b => [b.slug, b])),
        catSlugIndex: Object.fromEntries((masterCatBreeds || []).map(b => [b.slug, b])),
        dogIdIndex: Object.fromEntries((masterDogBreeds || []).map(b => [b.id, b])),
        catIdIndex: Object.fromEntries((masterCatBreeds || []).map(b => [b.id, b])),
        
        currentSection: 0,
        questionsPerSection: 10,
        get totalSections() { 
            return Math.ceil(this.personalityQuestions.length / this.questionsPerSection) 
        },
        
        currentQuestion: 0,
        personalityAnswers: {},
        attemptedNext: false,
        resultsSaved: false,
        hasSavedResults: hasSavedResults,
        recommendedBreeds: hasSavedResults ? savedResults.recommendedBreeds : [],
        
        personalityDimensions: {
            honestyHumility: { score: 0, max: 25 },
            emotionality: { score: 0, max: 25 },
            extraversion: { score: 0, max: 25 },
            agreeableness: { score: 0, max: 25 },
            conscientiousness: { score: 0, max: 25 },
            openness: { score: 0, max: 25 }
        },
        
        personalityQuestions: [
            // Honesty-Humility (5 questions)
            { question: "I would never accept a bribe, even if it were very large.", dimension: "honestyHumility", isReversed: false },
            { question: "I think I am entitled to more respect than the average person is.", dimension: "honestyHumility", isReversed: true },
            { question: "I wouldn't pretend to like someone just to get that person to do favors for me.", dimension: "honestyHumility", isReversed: false },
            { question: "I want people to know that I am an important person of high status.", dimension: "honestyHumility", isReversed: true },
            { question: "I wouldn't use flattery to get a raise or promotion at work, even if I thought it would succeed.", dimension: "honestyHumility", isReversed: false },
            
            // Emotionality (5 questions)
            { question: "I would feel afraid if I had to travel in bad weather conditions.", dimension: "emotionality", isReversed: false },
            { question: "I sometimes can't help worrying about little things.", dimension: "emotionality", isReversed: false },
            { question: "When I suffer from a painful experience, I need someone to make me feel comfortable.", dimension: "emotionality", isReversed: false },
            { question: "I feel like crying when I see other people crying.", dimension: "emotionality", isReversed: false },
            { question: "I remain unemotional even in situations where most people get very sentimental.", dimension: "emotionality", isReversed: true },
            
            // Extraversion (5 questions)
            { question: "I prefer jobs that involve active social interaction to those that involve working alone.", dimension: "extraversion", isReversed: false },
            { question: "The first thing that I always do in a new place is make friends.", dimension: "extraversion", isReversed: false },
            { question: "I enjoy having lots of people around to talk to.", dimension: "extraversion", isReversed: false },
            { question: "Most people are more upbeat and dynamic than I generally am.", dimension: "extraversion", isReversed: true },
            { question: "I rarely express my opinions in group meetings.", dimension: "extraversion", isReversed: true },
            
            // Agreeableness (5 questions)
            { question: "I rarely hold a grudge, even against people who have badly wronged me.", dimension: "agreeableness", isReversed: false },
            { question: "My attitude toward people who have treated me badly is 'forgive and forget'.", dimension: "agreeableness", isReversed: false },
            { question: "I tend to be lenient in judging other people.", dimension: "agreeableness", isReversed: false },
            { question: "Even when people make a lot of mistakes, I rarely say anything negative.", dimension: "agreeableness", isReversed: false },
            { question: "I tend to be stubborn in my attitudes.", dimension: "agreeableness", isReversed: true },
            
            // Conscientiousness (5 questions)
            { question: "I plan ahead and organize things to avoid scrambling at the last minute.", dimension: "conscientiousness", isReversed: false },
            { question: "When working, I sometimes have difficulties due to being disorganized.", dimension: "conscientiousness", isReversed: true },
            { question: "I often push myself very hard when trying to achieve a goal.", dimension: "conscientiousness", isReversed: false },
            { question: "I make decisions based on the feeling of the moment rather than on careful thought.", dimension: "conscientiousness", isReversed: true },
            { question: "I prefer to do whatever comes to mind, rather than stick to a plan.", dimension: "conscientiousness", isReversed: true },
            
            // Openness (5 questions)
            { question: "I would enjoy creating a work of art, such as a novel, a song, or a painting.", dimension: "openness", isReversed: false },
            { question: "I'm interested in learning about the history and politics of other countries.", dimension: "openness", isReversed: false },
            { question: "I would be quite bored with a visit to an art gallery.", dimension: "openness", isReversed: true },
            { question: "I enjoy looking at maps of different places.", dimension: "openness", isReversed: false },
            { question: "I would enjoy devising a new solution to a complex problem.", dimension: "openness", isReversed: false }
        ],
        
        selectPetType(type) {
            this.petType = type;
            this.currentStage = 'hairLength';
        },
        
        sectionBaseIndex() { return this.currentSection * this.questionsPerSection },
        
        sectionQuestions() {
            const start = this.sectionBaseIndex();
            return this.personalityQuestions.slice(start, start + this.questionsPerSection);
        },
        
        sectionAnsweredCount() {
            const start = this.sectionBaseIndex();
            let count = 0;
            for (let i = 0; i < this.questionsPerSection; i++) {
                if (this.personalityAnswers[start + i]) count++;
            }
            return count;
        },
        
        isSectionComplete() { 
            return this.sectionAnsweredCount() === Math.min(
                this.questionsPerSection, 
                this.personalityQuestions.length - this.sectionBaseIndex()
            ) 
        },
        
        answeredCount() { 
            return Object.keys(this.personalityAnswers).filter(k => this.personalityAnswers[k]).length 
        },
        
        nextSet() {
            if (!this.isSectionComplete()) { 
                this.attemptedNext = true; 
                return; 
            }
            this.attemptedNext = false;
            if (this.currentSection < this.totalSections - 1) {
                this.currentSection++;
            } else {
                this.calculatePersonalityScores();
                this.calculateResults();
                this.currentStage = 'results';
            }
        },
        
        prevSet() {
            this.attemptedNext = false;
            if (this.currentSection > 0) { 
                this.currentSection--; 
            } else { 
                this.currentStage = 'size'; 
            }
        },
        
        calculatePersonalityScores() {
            Object.keys(this.personalityDimensions).forEach(dim => {
                this.personalityDimensions[dim].score = 0;
            });
            
            this.personalityQuestions.forEach((q, index) => {
                const answer = parseInt(this.personalityAnswers[index] || 0);
                if (answer > 0) {
                    const score = q.isReversed ? (6 - answer) : answer;
                    this.personalityDimensions[q.dimension].score += score;
                }
            });
            
            console.log('Calculated personality scores:', this.personalityDimensions);
        },
        
        calculateResults() {
            const profile = {
                honestyScore: this.personalityDimensions.honestyHumility.score,
                emotionalityScore: this.personalityDimensions.emotionality.score,
                extraversionScore: this.personalityDimensions.extraversion.score,
                agreeablenessScore: this.personalityDimensions.agreeableness.score,
                conscientiousnessScore: this.personalityDimensions.conscientiousness.score,
                opennessScore: this.personalityDimensions.openness.score,
                highHonesty: this.personalityDimensions.honestyHumility.score >= 15,
                highEmotionality: this.personalityDimensions.emotionality.score >= 15,
                highExtraversion: this.personalityDimensions.extraversion.score >= 15,
                highAgreeableness: this.personalityDimensions.agreeableness.score >= 15,
                highConscientiousness: this.personalityDimensions.conscientiousness.score >= 15,
                highOpenness: this.personalityDimensions.openness.score >= 15
            };
            
            console.log('User personality profile:', profile);

            let dogBreeds = [];
            let catBreeds = [];
            
            if (hasSavedResults && savedResults.petType !== this.petType) {
                console.log("Pet type changed - clearing saved results");
                hasSavedResults = false;
                this.hasSavedResults = false;
                
                fetch('{{ route('assessment.save') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        petType: this.petType,
                        preferences: this.preferences,
                        recommendedBreeds: []
                    })
                }).catch(error => console.error('Error:', error));
            }
            
            if (hasSavedResults && this.currentStage === 'results' && 
                savedResults.recommendedBreeds.length > 0 && 
                savedResults.petType === this.petType) {
                console.log("Using saved breed recommendations");
                this.recommendedBreeds = savedResults.recommendedBreeds;
                return;
            }
            
            try {
                dogBreeds = JSON.parse('{!! addslashes($dogBreeds ?? "[]") !!}');
                catBreeds = JSON.parse('{!! addslashes($catBreeds ?? "[]") !!}');
            } catch (e) {
                console.error('Error parsing breeds:', e);
            }

            const breeds = this.petType === 'dog' ? dogBreeds : catBreeds;
            console.log(`Analyzing ${breeds.length} ${this.petType} breeds`);
            console.log(`User preferences: size=${this.preferences.size}, hairLength=${this.preferences.hairLength}`);

            const matchesPref = (actual, expected) => {
                if (!expected) return false;
                const a = String(actual || '').toLowerCase().replace(/[^a-z0-9]+/g, ' ');
                const e = String(expected || '').toLowerCase().replace(/[^a-z0-9]+/g, ' ');
                if (a === e) return true;
                if (a.includes(e)) return true;
                if (e.includes(a)) return true;
                if (a.indexOf('short') !== -1 && e.indexOf('short') !== -1) return true;
                if (a.indexOf('long') !== -1 && e.indexOf('long') !== -1) return true;
                return false;
            };

            let sourceBreeds = breeds;
            let hairConflictPenalty = 0;
            if (this.preferences && this.preferences.hairLength) {
                const requested = String(this.preferences.hairLength).toLowerCase();
                const explicitMatches = breeds.filter(b => (b.hairLength || '') !== '' && matchesPref(b.hairLength, requested));
                console.log(`Explicit hairLength matches for '${requested}': ${explicitMatches.length}`);
                if (explicitMatches.length > 0) {
                    sourceBreeds = explicitMatches;
                } else {
                    hairConflictPenalty = 40;
                    console.log('No explicit hairLength matches; will penalize breeds whose hairLength conflicts with preference');
                }
            }
            
            let exactMatches = sourceBreeds.filter(breed => 
                matchesPref(breed.hairLength, this.preferences.hairLength) &&
                matchesPref(breed.size, this.preferences.size)
            );
            console.log(`✓ Exact matches: ${exactMatches.length}`);
            
            let partialMatches = [];
            if (exactMatches.length < 5) {
                partialMatches = sourceBreeds.filter(breed => 
                    (matchesPref(breed.hairLength, this.preferences.hairLength) ||
                     matchesPref(breed.size, this.preferences.size)) &&
                    !exactMatches.includes(breed)
                );
                console.log(`✓ Partial matches: ${partialMatches.length}`);
            }
            
            const scoreBreed = (breed) => {
                let score = 0;
                let matchDetails = [];
                
                if (matchesPref(breed.hairLength, this.preferences.hairLength)) {
                    score += 25;
                    matchDetails.push('✓ Hair length');
                } else if (hairConflictPenalty > 0 && (breed.hairLength || '') !== '' && !matchesPref(breed.hairLength, this.preferences.hairLength)) {
                    score -= hairConflictPenalty;
                    matchDetails.push('✗ Hair length conflict');
                }
                if (matchesPref(breed.size, this.preferences.size)) {
                    score += 25;
                    matchDetails.push('✓ Size');
                }
                
                if (breed.personalityMatch && Array.isArray(breed.personalityMatch)) {
                    breed.personalityMatch.forEach(trait => {
                        if (profile[trait]) {
                            score += 15;
                            matchDetails.push(`✓ ${trait}`);
                        }
                        
                        const traitScoreMap = {
                            'highHonesty': profile.honestyScore,
                            'highEmotionality': profile.emotionalityScore,
                            'highExtraversion': profile.extraversionScore,
                            'highAgreeableness': profile.agreeablenessScore,
                            'highConscientiousness': profile.conscientiousnessScore,
                            'highOpenness': profile.opennessScore
                        };
                        
                        const actualScore = traitScoreMap[trait] || 0;
                        score += (actualScore / 25) * 5;
                    });
                }
                
                const personalityMatchCount = (breed.personalityMatch || []).filter(t => profile[t]).length;
                if (personalityMatchCount >= 3) {
                    score += 10;
                    matchDetails.push('★ Strong personality match');
                } else if (personalityMatchCount >= 2) {
                    score += 5;
                }
                
                breed.matchScore = Math.round(score * 100) / 100;
                breed.matchDetails = matchDetails;
                
                return score;
            };
            
            exactMatches.forEach(breed => scoreBreed(breed));
            partialMatches.forEach(breed => {
                const baseScore = scoreBreed(breed);
                breed.matchScore = baseScore * 0.7;
            });
            
            let allCandidates = [...exactMatches, ...partialMatches];
            
            if (allCandidates.length < 5) {
                const remaining = breeds.filter(b => !allCandidates.includes(b));
                remaining.forEach(breed => {
                    const baseScore = scoreBreed(breed);
                    breed.matchScore = baseScore * 0.5;
                });
                allCandidates = [...allCandidates, ...remaining];
            }
            
            allCandidates.sort((a, b) => b.matchScore - a.matchScore);

            const topScore = allCandidates.length ? allCandidates[0].matchScore : 0;
            const dynamicThreshold = Math.max(30, topScore * 0.7);

            let selected = [];
            if (exactMatches.length > 0) {
                exactMatches.sort((a, b) => b.matchScore - a.matchScore);
                const topExact = exactMatches[0].matchScore;
                const exactThreshold = Math.max(dynamicThreshold, topExact * 0.8);
                selected = exactMatches.filter(b => b.matchScore >= exactThreshold);
                if (selected.length === 0 && exactMatches.length > 0) {
                    selected = [exactMatches[0]];
                }
            } else {
                selected = allCandidates.filter(b => b.matchScore >= dynamicThreshold);
            }

            if (selected.length === 0 && allCandidates.length > 0) {
                selected = [allCandidates[0]];
            }

            selected = selected.slice(0, 3);

            console.log('═══ SELECTED MATCHES (dynamic) ═══');
            selected.forEach((breed, i) => {
                console.log(`${i + 1}. ${breed.name} (Score: ${breed.matchScore})`);
                console.log(`   Size: ${breed.size} | Hair: ${breed.hairLength}`);
                console.log(`   Personalities: ${breed.personalityMatch?.join(', ') || 'None'}`);
                console.log(`   ${breed.matchDetails.join(' | ')}`);
            });

            this.recommendedBreeds = selected;
            
            this.recommendedBreeds.forEach(breed => {
                if (!breed.traits || !Array.isArray(breed.traits)) {
                    breed.traits = [];
                }
                if (!breed.image) {
                    breed.image = '/placeholder.svg?height=300&width=400';
                }
            });
        },

        getBreedImage(breed) {
            const placeholder = '/placeholder.svg?height=300&width=400';
            if (!breed) return placeholder;
            
            const slugMap = this.petType === 'dog' ? this.dogSlugIndex : this.catSlugIndex;
            if (breed.slug && slugMap[breed.slug] && slugMap[breed.slug].image) {
                return slugMap[breed.slug].image;
            }
            
            const idMap = this.petType === 'dog' ? this.dogIdIndex : this.catIdIndex;
            if (breed.id && idMap[breed.id] && idMap[breed.id].image) {
                return idMap[breed.id].image;
            }
            
            return breed.image || placeholder;
        },
        
        async saveResults() {
            const results = {
                petType: this.petType,
                preferences: this.preferences,
                recommendedBreeds: this.recommendedBreeds.map(breed => ({
                    id: breed.id,
                    slug: breed.slug,
                    name: breed.name,
                    image: breed.image,
                    description: breed.description,
                    traits: breed.traits,
                    size: breed.size,
                    hairLength: breed.hairLength,
                    matchScore: breed.matchScore
                })),
                personalityScores: (function(dims){
                    const to7 = (score, max) => {
                        const s = Number(score) || 0;
                        const m = Number(max) || 1;
                        const scaled = Math.round((s / m) * 6) + 1;
                        return Math.min(Math.max(scaled, 1), 7);
                    };
                    return {
                        honesty: to7(dims.honestyHumility.score, dims.honestyHumility.max),
                        emotionality: to7(dims.emotionality.score, dims.emotionality.max),
                        extraversion: to7(dims.extraversion.score, dims.extraversion.max),
                        agreeableness: to7(dims.agreeableness.score, dims.agreeableness.max),
                        conscientiousness: to7(dims.conscientiousness.score, dims.conscientiousness.max),
                        openness: to7(dims.openness.score, dims.openness.max)
                    };
                })(this.personalityDimensions)
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
                const notification = document.createElement('div');
                notification.className = 'notification-toast';
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

                setTimeout(() => {
                    notification.style.animation = 'slideOutRight 0.5s ease';
                    setTimeout(() => notification.remove(), 500);
                }, 3000);
            } catch (error) {
                console.error('Error saving results:', error);
                alert('Error saving results. Please try again.');
            }
        },
        
        restart() {
            this.currentStage = 'intro';
            this.petType = null;
            this.preferences = { hairLength: null, size: null };
            this.currentSection = 0;
            this.currentQuestion = 0;
            this.personalityAnswers = {};
            this.attemptedNext = false;
            this.recommendedBreeds = [];
            this.resultsSaved = false;
            this.hasSavedResults = false;
            
            Object.keys(this.personalityDimensions).forEach(dim => {
                this.personalityDimensions[dim].score = 0;
            });
            
            fetch('{{ route('assessment.save') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    petType: null,
                    preferences: null,
                    recommendedBreeds: []
                })
            })
            .then(() => {
                window.location.href = '{{ route('assessment') }}?reset=true&start=petType&t=' + new Date().getTime();
            })
            .catch(error => {
                console.error('Error:', error);
                window.location.href = '{{ route('assessment') }}?reset=true&start=petType&t=' + new Date().getTime();
            });
        }
    };
}
    </script>
@endsection