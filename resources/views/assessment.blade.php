@extends('layouts.app')

@section('title', 'Personality Assessment')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="flex flex-col min-h-screen" x-data="quizApp()">
        
        @if(Session::has('assessment_saved'))
        <!-- Flash message after saving results -->
        <div class="bg-green-100 p-4 my-4 rounded text-center" x-data="{ show: true }" x-init="setTimeout(() => { show = false }, 5000)" x-show="show" x-transition>
            <h3 class="font-bold text-green-800">Assessment Saved!</h3>
            <p class="text-green-700">Your assessment results have been successfully saved.</p>
        </div>
        @endif
        
        <!-- Main Content -->
        <main class="flex-1">
            <div class="container py-8 max-w-3xl mx-auto">
                <!-- Introduction -->
                <template x-if="currentStage === 'intro'">
                    <div class="text-center">
                        <h2 class="text-3xl font-bold mb-6">Pet Personality Matcher</h2>
                        <p class="mb-8">Find the perfect pet match based on your personality and preferences</p>
                        
                        <div class="flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4 justify-center">
                            <button class="text-white bg-[#24292F] hover:bg-[#24292F]/90 focus:ring-4 focus:outline-none focus:ring-[#24292F]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-500 dark:hover:bg-[#050708]/30" 
                                @click="currentStage = 'petType'">
                                Start New Assessment
                            </button>
                            
                            @if(Session::has('assessment_results'))
                                <a href="#results" class="text-[#24292F] bg-white border border-[#24292F] hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-[#24292F]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center"
                                   @click.prevent="currentStage = 'results'">
                                    View Your Results
                                </a>
                            @endif
                            
                            @auth
                                @if(auth()->user()->assessments->count() > 0)
                                    <a href="{{ route('profile.edit') }}" class="text-blue-600 bg-white border border-blue-600 hover:bg-blue-50 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center">
                                        View All Your Assessments
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </template>

                <!-- Step 1: Choose pet type -->
                <template x-if="currentStage === 'petType'">
                    <div>
                        <h2 class="text-2xl font-bold mb-4">Step 1: Choose your preferred pet type</h2>
                        <div class="grid grid-cols-2 gap-6">
                            <button class="p-8 border rounded-lg hover:bg-blue-50 transition flex flex-col items-center" 
                                @click="selectPetType('dog')">
                                <span class="text-5xl mb-3">🐶</span>
                                <span class="font-medium">Dog</span>
                            </button>
                            <button class="p-8 border rounded-lg hover:bg-blue-50 transition flex flex-col items-center" 
                                @click="selectPetType('cat')">
                                <span class="text-5xl mb-3">🐱</span>
                                <span class="font-medium">Cat</span>
                            </button>
                        </div>
                    </div>
                </template>

                <!-- Step 2: Choose hair length -->
                <template x-if="currentStage === 'hairLength'">
                    <div>
                        <h2 class="text-2xl font-bold mb-4">Step 2: Preferred hair length?</h2>
                        <div class="grid grid-cols-2 gap-6">
                            <button class="p-6 border rounded-lg hover:bg-blue-50 transition" 
                                @click="preferences.hairLength = 'short'; currentStage = 'size'">
                                <div class="h-32 flex items-center justify-center mb-3">
                                    <img :src="petType === 'dog' ? 'https://placedog.net/400/300?id=7' : 'https://placekitten.com/400/300?image=9'" 
                                        alt="Short hair" class="h-full object-cover rounded">
                                </div>
                                <p class="text-center font-medium">Short Hair</p>
                            </button>
                            <button class="p-6 border rounded-lg hover:bg-blue-50 transition" 
                                @click="preferences.hairLength = 'long'; currentStage = 'size'">
                                <div class="h-32 flex items-center justify-center mb-3">
                                    <img :src="petType === 'dog' ? 'https://placedog.net/400/300?id=8' : 'https://placekitten.com/400/300?image=3'" 
                                        alt="Long hair" class="h-full object-cover rounded">
                                </div>
                                <p class="text-center font-medium">Long Hair</p>
                            </button>
                        </div>
                        <button class="mt-5 text-white bg-[#24292F] hover:bg-[#24292F]/90 focus:ring-4 focus:outline-none focus:ring-[#24292F]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-500 dark:hover:bg-[#050708]/30 me-2 mb-2" 
                            @click="currentStage = 'petType'">Back</button>
                    </div>
                </template>

                <!-- Step 3: Choose size -->
                <template x-if="currentStage === 'size'">
                    <div>
                        <h2 class="text-2xl font-bold mb-4">Step 3: Preferred size?</h2>
                        <div class="grid grid-cols-3 gap-4">
                            <button class="p-6 border rounded-lg hover:bg-blue-50 transition" 
                                @click="preferences.size = 'small'; currentStage = 'personality'">
                                <div class="h-24 flex items-center justify-center mb-3">
                                    <span class="text-4xl">🐕</span>
                                </div>
                                <p class="text-center font-medium">Small</p>
                            </button>
                            <button class="p-6 border rounded-lg hover:bg-blue-50 transition" 
                                @click="preferences.size = 'medium'; currentStage = 'personality'">
                                <div class="h-24 flex items-center justify-center mb-3">
                                    <span class="text-5xl">🐕</span>
                                </div>
                                <p class="text-center font-medium">Medium</p>
                            </button>
                            <button class="p-6 border rounded-lg hover:bg-blue-50 transition" 
                                @click="preferences.size = 'large'; currentStage = 'personality'">
                                <div class="h-24 flex items-center justify-center mb-3">
                                    <span class="text-6xl">🐕</span>
                                </div>
                                <p class="text-center font-medium">Large</p>
                            </button>
                        </div>
                        <button class="mt-5 text-white bg-[#24292F] hover:bg-[#24292F]/90 focus:ring-4 focus:outline-none focus:ring-[#24292F]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-500 dark:hover:bg-[#050708]/30 me-2 mb-2" 
                            @click="currentStage = 'hairLength'">Back</button>
                    </div>
                </template>

                <!-- Step 4: Personality Questions -->
                <template x-if="currentStage === 'personality'">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">Personality Assessment</h2>
                        <p class="text-gray-600 mb-4">Question <span x-text="currentQuestion + 1"></span> of <span x-text="personalityQuestions.length"></span></p>
                        
                        <!-- Required field notification -->
                        <p class="text-sm text-gray-600 mb-4">* All questions require an answer</p>

                        <!-- Progress Bar -->
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-6">
                            <div class="bg-blue-500 h-2.5 rounded-full"
                                :style="`width: ${((currentQuestion+1)/personalityQuestions.length)*100}%`">
                            </div>
                        </div>

                        <div class="border rounded-lg p-6 mb-6 bg-white shadow-sm">
                            <h3 class="text-lg font-medium mb-6" x-text="personalityQuestions[currentQuestion].question"></h3>
                            
                            <div class="grid grid-cols-5 gap-2 text-center">
                                <div class="text-sm text-gray-600">Strongly Disagree</div>
                                <div class="col-span-3"></div>
                                <div class="text-sm text-gray-600">Strongly Agree</div>
                            </div>
                            
                            <div class="flex justify-between items-center mt-2 mb-4">
                                <template x-for="n in 5" :key="n">
                                    <label class="flex flex-col items-center cursor-pointer">
                                        <input type="radio" :name="'q'+currentQuestion" :value="n" 
                                            x-model="personalityAnswers[currentQuestion]" 
                                            class="mb-2 h-4 w-4 accent-blue-500 transition duration-200">
                                        <span class="text-sm" x-text="n"></span>
                                    </label>
                                </template>
                            </div>
                            
                            <!-- Selection indicator -->
                            <div class="flex justify-center mt-2" x-show="!personalityAnswers[currentQuestion]">
                                <p class="text-sm text-amber-600" x-show="attemptedNext"
                                   x-transition:enter="transition ease-out duration-300"
                                   x-transition:enter-start="opacity-0 transform translate-y-2"
                                   x-transition:enter-end="opacity-100 transform translate-y-0">
                                   Please select one option above
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button class="text-white bg-[#24292F] hover:bg-[#24292F]/90 focus:ring-4 focus:outline-none focus:ring-[#24292F]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-500 dark:hover:bg-[#050708]/30 me-2 mb-2" 
                                @click="prevQuestion" x-show="currentQuestion > 0 || currentStage !== 'personality'">
                                Back
                            </button>
                            <button class="text-white bg-[#24292F] hover:bg-[#24292F]/90 focus:ring-4 focus:outline-none focus:ring-[#24292F]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-500 dark:hover:bg-[#050708]/30 me-2 mb-2" 
                                @click="currentStage = 'size'" x-show="currentQuestion === 0">
                                Back to Preferences
                            </button>
                            <div class="relative" x-data="{ showTooltip: false }">
                                <button class="text-white bg-[#24292F] hover:bg-[#24292F]/90 focus:ring-4 focus:outline-none focus:ring-[#24292F]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-500 dark:hover:bg-[#050708]/30 me-2 mb-2" 
                                    @click="personalityAnswers[currentQuestion] ? nextQuestion() : showTooltip = true"
                                    @mouseleave="showTooltip = false">
                                    <span x-show="currentQuestion < personalityQuestions.length - 1">Next</span>
                                    <span x-show="currentQuestion === personalityQuestions.length - 1">See Results</span>
                                </button>
                                <!-- Validation tooltip -->
                                <div x-show="showTooltip && !personalityAnswers[currentQuestion]" 
                                    class="absolute bottom-full mb-2 right-0 bg-red-100 text-red-700 px-3 py-1 rounded shadow-sm text-sm"
                                    style="width: max-content;">
                                    Please select an answer before proceeding
                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-l-4 border-r-4 border-t-4 border-transparent border-t-red-100" style="border-width: 6px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Results -->
                <template x-if="currentStage === 'results'">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">Your Perfect Pet Matches</h2>
                        <p class="mb-6">Based on your personality and preferences, here are the top 3 breeds we recommend:</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <template x-for="(breed, index) in recommendedBreeds" :key="index">
                                <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition">
                                    <img :src="breed.image" :alt="breed.name"
                                        class="w-full h-48 object-cover">
                                    <div class="p-4">
                                        <h3 class="font-bold text-lg mb-1" x-text="breed.name"></h3>
                                        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                                            <span x-text="preferences.size.charAt(0).toUpperCase() + preferences.size.slice(1)"></span>
                                            <span>•</span>
                                            <span x-text="preferences.hairLength.charAt(0).toUpperCase() + preferences.hairLength.slice(1) + ' Hair'"></span>
                                        </div>
                                        <p class="text-sm text-gray-700 h-20 overflow-hidden" x-text="breed.description.length > 100 ? breed.description.substring(0, 100) + '...' : breed.description"></p>
                                        <div class="mt-3 pt-3 border-t">
                                            <div class="text-sm font-medium mb-1">Your Personality Traits:</div>
                                            <div class="flex flex-wrap gap-2">
                                                <template x-for="(trait, i) in breed.traits" :key="i">
                                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded" 
                                                        x-text="trait"></span>
                                                </template>
                                            </div>
                                        </div>
                                        
                                        <!-- Add link to view more details -->
                                        <div class="mt-4 text-center">
                                            <a :href="'/' + petType + 's/' + breed.slug" 
                                               class="text-white bg-[#24292F] hover:bg-[#24292F]/90 focus:ring-4 focus:outline-none focus:ring-[#24292F]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-500 dark:hover:bg-[#050708]/30 me-2 mb-2">
                                               View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="text-center mb-4">
                            @auth
                                <!-- Only show save button if results aren't already saved and user is logged in -->
                                <button x-show="!resultsSaved && !hasSavedResults" class="px-6 py-2 bg-green-500 text-white rounded hover:bg-green-600 mr-4" 
                                    @click="saveResults">Save Results</button>
                                    
                                <!-- Show "Saved!" indicator only when user just saved results, not when returning to page -->
                                <span x-show="resultsSaved && !hasSavedResults" class="text-white bg-[#050708] hover:bg-[#050708]/90 focus:ring-4 focus:outline-none focus:ring-[#050708]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#050708]/50 dark:hover:bg-[#050708]/30 me-2 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Results Saved
                                </span>
                            @else
                                <!-- Show login button if user is not authenticated -->
                                <a href="{{ route('login') }}" class="px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mr-4 inline-block">
                                    Login to Save Results
                                </a>
                            @endauth
                            
                            <button class="text-white bg-[#24292F] hover:bg-[#24292F]/90 focus:ring-4 focus:outline-none focus:ring-[#24292F]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-500 dark:hover:bg-[#050708]/30 me-2 mb-2" 
                                @click="restart">Retake Personality Breed Assessment</button>
                        </div>
                        
                        <!-- Only show the "Your results have been saved" message when user just saved results, not when returning -->
                        <div class="mt-4 p-4 bg-green-100 text-green-800 rounded-lg" 
                            x-show="resultsSaved && !hasSavedResults"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0">
                            <p class="font-medium">Your results have been saved!</p>
                            <p class="text-sm mt-1">You can access them anytime you return to this assessment page.</p>
                        </div>
                    </div>
                </template>

            </div>
        </main>
    </div>

    <!-- Alpine.js State & Logic -->
    <script>
        function quizApp() {
            // Use safer initialization with try/catch
            let savedResults = { petType: null, preferences: null, recommendedBreeds: [] };
            let hasSavedResults = false;
            
            try {
                // Check if the variable exists and is valid JSON
                if (typeof '{!! addslashes($savedResults) !!}' === 'string') {
                    const savedResultsJson = '{!! addslashes($savedResults) !!}';
                    console.log('Raw saved results from server:', savedResultsJson);
                    
                    savedResults = JSON.parse(savedResultsJson);
                    // Only consider saved results valid if they have the proper data
                    hasSavedResults = savedResults && 
                                      savedResults.petType !== null && 
                                      savedResults.preferences && 
                                      savedResults.recommendedBreeds && 
                                      savedResults.recommendedBreeds.length > 0;
                    
                    // Check if there are saved results in URL parameters (for when returning from another page)
                    const urlParams = new URLSearchParams(window.location.search);
                    const resetParam = urlParams.get('reset');
                    
                    // If reset=true in URL, clear saved results state
                    if (resetParam === 'true') {
                        hasSavedResults = false;
                        savedResults = { petType: null, preferences: null, recommendedBreeds: [] };
                        // Remove the parameter to avoid loops
                        window.history.replaceState({}, document.title, window.location.pathname);
                    }
                    
                    // Log the saved results for debugging
                    console.log('Saved results found:', hasSavedResults);
                    if (hasSavedResults) {
                        console.log('Pet type:', savedResults.petType);
                        console.log('Preferences:', savedResults.preferences);
                        console.log('Recommended breeds count:', savedResults.recommendedBreeds.length);
                        
                        // Print the first breed to verify we have complete data
                        if (savedResults.recommendedBreeds.length > 0) {
                            console.log('First breed sample:', {
                                name: savedResults.recommendedBreeds[0].name,
                                id: savedResults.recommendedBreeds[0].id,
                                image: savedResults.recommendedBreeds[0].image ? 'Has image' : 'No image'
                            });
                        }
                    }
                }
            } catch (e) {
                console.error('Error parsing saved results:', e);
            }
            
            // Create a function to check if pet type is valid
            const isValidPetType = (type) => type === 'dog' || type === 'cat';
            
            // Make sure we have valid saved results with proper pet type
            const hasValidSavedResults = hasSavedResults && 
                isValidPetType(savedResults.petType) && 
                savedResults.recommendedBreeds && 
                savedResults.recommendedBreeds.length > 0;
            
            if (hasSavedResults && !hasValidSavedResults) {
                console.warn('Invalid or incomplete saved results detected', savedResults);
            }
            
            return {
                // Only start with results if we have valid saved results
                currentStage: hasValidSavedResults ? 'results' : 'intro', // intro, petType, hairLength, size, personality, results
                petType: hasValidSavedResults ? savedResults.petType : null,
                preferences: hasSavedResults ? savedResults.preferences : {
                    hairLength: null, // short, long
                    size: null, // small, medium, large
                },
                currentQuestion: 0,
                personalityAnswers: {},
                attemptedNext: false,
                resultsSaved: false, // Initially false, only true when user manually saves results
                hasSavedResults: hasSavedResults, // True when returning with saved results
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
                    // Honesty-Humility Dimension
                    {
                        question: "I would never accept a bribe, even if it were very large.",
                        dimension: "honestyHumility",
                        isReversed: false
                    },
                    {
                        question: "I think I am entitled to more respect than the average person is.",
                        dimension: "honestyHumility",
                        isReversed: true
                    },
                    {
                        question: "I wouldn't pretend to like someone just to get that person to do favors for me.",
                        dimension: "honestyHumility",
                        isReversed: false
                    },
                    {
                        question: "I want people to know that I am an important person of high status.",
                        dimension: "honestyHumility",
                        isReversed: true
                    },
                    {
                        question: "I wouldn't use flattery to get a raise or promotion at work, even if I thought it would succeed.",
                        dimension: "honestyHumility",
                        isReversed: false
                    },
                    // Emotionality Dimension
                    {
                        question: "I would feel afraid if I had to travel in bad weather conditions.",
                        dimension: "emotionality",
                        isReversed: false
                    },
                    {
                        question: "I sometimes can't help worrying about little things.",
                        dimension: "emotionality",
                        isReversed: false
                    },
                    {
                        question: "When I suffer from a painful experience, I need someone to make me feel comfortable.",
                        dimension: "emotionality",
                        isReversed: false
                    },
                    {
                        question: "I feel like crying when I see other people crying.",
                        dimension: "emotionality",
                        isReversed: false
                    },
                    {
                        question: "I remain unemotional even in situations where most people get very sentimental.",
                        dimension: "emotionality",
                        isReversed: true
                    },
                    // Extraversion Dimension
                    {
                        question: "I prefer jobs that involve active social interaction to those that involve working alone.",
                        dimension: "extraversion",
                        isReversed: false
                    },
                    {
                        question: "The first thing that I always do in a new place is make friends.",
                        dimension: "extraversion",
                        isReversed: false
                    },
                    {
                        question: "I enjoy having lots of people around to talk to.",
                        dimension: "extraversion",
                        isReversed: false
                    },
                    {
                        question: "Most people are more upbeat and dynamic than I generally am.",
                        dimension: "extraversion",
                        isReversed: true
                    },
                    {
                        question: "I rarely express my opinions in group meetings.",
                        dimension: "extraversion",
                        isReversed: true
                    },
                    // Agreeableness Dimension
                    {
                        question: "I rarely hold a grudge, even against people who have badly wronged me.",
                        dimension: "agreeableness",
                        isReversed: false
                    },
                    {
                        question: "My attitude toward people who have treated me badly is 'forgive and forget'.",
                        dimension: "agreeableness",
                        isReversed: false
                    },
                    {
                        question: "I tend to be lenient in judging other people.",
                        dimension: "agreeableness",
                        isReversed: false
                    },
                    {
                        question: "Even when people make a lot of mistakes, I rarely say anything negative.",
                        dimension: "agreeableness",
                        isReversed: false
                    },
                    {
                        question: "I tend to be stubborn in my attitudes.",
                        dimension: "agreeableness",
                        isReversed: true
                    },
                    // Conscientiousness Dimension
                    {
                        question: "I plan ahead and organize things to avoid scrambling at the last minute.",
                        dimension: "conscientiousness",
                        isReversed: false
                    },
                    {
                        question: "When working, I sometimes have difficulties due to being disorganized.",
                        dimension: "conscientiousness",
                        isReversed: true
                    },
                    {
                        question: "I often push myself very hard when trying to achieve a goal.",
                        dimension: "conscientiousness",
                        isReversed: false
                    },
                    {
                        question: "I make decisions based on the feeling of the moment rather than on careful thought.",
                        dimension: "conscientiousness",
                        isReversed: true
                    },
                    {
                        question: "I prefer to do whatever comes to mind, rather than stick to a plan.",
                        dimension: "conscientiousness",
                        isReversed: true
                    },
                    // Openness to Experience Dimension
                    {
                        question: "I would enjoy creating a work of art, such as a novel, a song, or a painting.",
                        dimension: "openness",
                        isReversed: false
                    },
                    {
                        question: "I'm interested in learning about the history and politics of other countries.",
                        dimension: "openness",
                        isReversed: false
                    },
                    {
                        question: "I would be quite bored with a visit to an art gallery.",
                        dimension: "openness",
                        isReversed: true
                    },
                    {
                        question: "I enjoy looking at maps of different places.",
                        dimension: "openness",
                        isReversed: false
                    },
                    {
                        question: "I would enjoy devising a new solution to a complex problem.",
                        dimension: "openness",
                        isReversed: false
                    }
                ],
                
                selectPetType(type) {
                    this.petType = type;
                    this.currentStage = 'hairLength';
                },
                
                nextQuestion() {
                    if (!this.personalityAnswers[this.currentQuestion]) {
                        this.attemptedNext = true;
                        return false;
                    }
                    
                    this.attemptedNext = false;
                    
                    if (this.currentQuestion < this.personalityQuestions.length - 1) {
                        this.currentQuestion++;
                    } else {
                        this.calculatePersonalityScores();
                        this.calculateResults();
                        this.currentStage = 'results';
                    }
                },
                
                prevQuestion() {
                    if (this.currentQuestion > 0) {
                        this.currentQuestion--;
                    } else {
                        this.currentStage = 'size';
                    }
                },
                
                calculatePersonalityScores() {
                    // Reset scores
                    Object.keys(this.personalityDimensions).forEach(dim => {
                        this.personalityDimensions[dim].score = 0;
                    });
                    
                    // Calculate scores based on answers
                    this.personalityQuestions.forEach((q, index) => {
                        const answer = parseInt(this.personalityAnswers[index] || 0);
                        if (answer > 0) {
                            const score = q.isReversed ? (6 - answer) : answer;
                            this.personalityDimensions[q.dimension].score += score;
                        }
                    });
                },
                
                calculateResults() {
                    // Get personality profile
                    const profile = {
                        highHonesty: this.personalityDimensions.honestyHumility.score > 15,
                        highEmotionality: this.personalityDimensions.emotionality.score > 15,
                        highExtraversion: this.personalityDimensions.extraversion.score > 15,
                        highAgreeableness: this.personalityDimensions.agreeableness.score > 15,
                        highConscientiousness: this.personalityDimensions.conscientiousness.score > 15,
                        highOpenness: this.personalityDimensions.openness.score > 15
                    };

                    // Use breed data from the database (passed from the controller)
                    let dogBreeds = [];
                    let catBreeds = [];
                    
                    // IMPORTANT: If the saved pet type doesn't match the current pet type,
                    // we need to force a recalculation by clearing saved results
                    if (hasSavedResults && savedResults.petType !== this.petType) {
                        console.log("Pet type changed from", savedResults.petType, "to", this.petType, "- clearing saved results");
                        hasSavedResults = false;
                        this.hasSavedResults = false;
                        
                        // Clear saved results from the session via AJAX
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
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Old results cleared due to pet type change:', data);
                        })
                        .catch(error => {
                            console.error('Error clearing old results:', error);
                        });
                    }
                    
                    // If we're coming back to the page with saved results and we're in the results stage,
                    // use the saved recommended breeds instead of calculating them again,
                    // but only if the pet type matches (to prevent showing dog breeds when cats are selected)
                    if (hasSavedResults && this.currentStage === 'results' && 
                        savedResults.recommendedBreeds.length > 0 && 
                        savedResults.petType === this.petType) {
                        console.log("Using saved breed recommendations");
                        this.recommendedBreeds = savedResults.recommendedBreeds;
                        return; // Exit early, we already have results
                    }
                    
                    try {
                        dogBreeds = JSON.parse('{!! addslashes($dogBreeds ?? "[]") !!}');
                        console.log(`Loaded ${dogBreeds.length} dog breeds`);
                    } catch (e) {
                        console.error('Error parsing dog breeds:', e);
                        dogBreeds = [];
                    }
                    
                    try {
                        catBreeds = JSON.parse('{!! addslashes($catBreeds ?? "[]") !!}');
                        console.log(`Loaded ${catBreeds.length} cat breeds`);
                    } catch (e) {
                        console.error('Error parsing cat breeds:', e);
                        catBreeds = [];
                    }

                    // Select the breed database based on pet type
                    // IMPORTANT: Always respect the current pet type selection
                    const breeds = this.petType === 'dog' ? dogBreeds : catBreeds;
                    console.log(`Using ${this.petType} breeds: ${breeds.length} available`);
                    
                    // Filter by preferences
                    let filteredBreeds = breeds.filter(breed => 
                        breed.hairLength === this.preferences.hairLength && 
                        breed.size === this.preferences.size
                    );
                    
                    // If not enough matches with exact preferences, loosen the criteria
                    if (filteredBreeds.length < 3) {
                        filteredBreeds = breeds.filter(breed => 
                            breed.hairLength === this.preferences.hairLength || 
                            breed.size === this.preferences.size
                        );
                    }
                    
                    // If still not enough, use all breeds
                    if (filteredBreeds.length < 3) {
                        filteredBreeds = breeds;
                    }
                    
                    // Score each breed based on personality match
                    filteredBreeds.forEach(breed => {
                        breed.matchScore = 0;
                        breed.personalityMatch.forEach(trait => {
                            if (profile[trait]) {
                                breed.matchScore += 1;
                            }
                        });
                    });
                    
                    // Sort by match score
                    filteredBreeds.sort((a, b) => b.matchScore - a.matchScore);
                    
                    // Take top 3
                    this.recommendedBreeds = filteredBreeds.slice(0, 3);
                },
                
                saveResults() {
                    // Prepare data for saving - send complete breed info to be stored in session
                    const results = {
                        petType: this.petType,
                        preferences: this.preferences,
                        recommendedBreeds: this.recommendedBreeds.map(breed => ({
                            id: breed.id,
                            slug: breed.slug,
                            name: breed.name,
                            // Include these additional properties to make sure they're preserved
                            image: breed.image,
                            description: breed.description,
                            traits: breed.traits
                        }))
                    };
                    
                    // Send AJAX request to save results
                    fetch('{{ route('assessment.save') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(results)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.resultsSaved = true;
                            // We're explicitly not setting hasSavedResults to distinguish between
                            // just-saved results and results loaded from a previous session
                            
                            // Add a notification that the results were saved
                            const notification = document.createElement('div');
                            notification.className = 'fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg';
                            notification.textContent = 'Your results have been saved!';
                            document.body.appendChild(notification);
                            
                            // Remove notification after 3 seconds
                            setTimeout(() => {
                                notification.remove();
                            }, 3000);
                        }
                    })
                    .catch(error => {
                        console.error('Error saving results:', error);
                    });
                },
                
                restart() {
                    this.currentStage = 'intro';
                    this.petType = null;
                    this.preferences = {
                        hairLength: null,
                        size: null
                    };
                    this.currentQuestion = 0;
                    this.personalityAnswers = {};
                    this.attemptedNext = false;
                    this.recommendedBreeds = [];
                    this.resultsSaved = false;
                    this.hasSavedResults = false; // Reset the saved results flag
                    
                    // Reset personality scores
                    Object.keys(this.personalityDimensions).forEach(dim => {
                        this.personalityDimensions[dim].score = 0;
                    });
                    
                    console.log('Clearing saved assessment data and reloading page');
                    
                    // Clear saved results from the session via AJAX
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
                    .then(response => response.json())
                    .then(data => {
                        console.log('Assessment data cleared:', data);
                        // Force a hard reload with the reset parameter to ensure clean state
                        window.location.href = '{{ route('assessment') }}?reset=true&t=' + new Date().getTime();
                    })
                    .catch(error => {
                        console.error('Error clearing assessment data:', error);
                        // Even if there's an error, try to reload with reset param
                        window.location.href = '{{ route('assessment') }}?reset=true&t=' + new Date().getTime();
                    });
                }
            };
        }
    </script>
@endsection
