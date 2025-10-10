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
                                    <img :src="petType === 'dog' ? 'https://topdogtips.com/wp-content/uploads/2017/04/Best-short-hair-dog-breeds-16.jpg' : 'https://www.petrescueblog.com/wp-content/uploads/2021/01/e8901c74e0ffaebaac19d375c30c39b8-1140x855.jpg'" 
                                        alt="Short hair" class="h-full object-cover rounded">
                                </div>
                                <p class="text-center font-medium">Short Hair</p>
                            </button>
                            <button class="p-6 border rounded-lg hover:bg-blue-50 transition" 
                                @click="preferences.hairLength = 'long'; currentStage = 'size'">
                                <div class="h-32 flex items-center justify-center mb-3">
                                    <img :src="petType === 'dog' ? 'https://tse1.mm.bing.net/th/id/OIP.oj0himbKqq-E9Qz_x8EYrwHaHa?cb=12&rs=1&pid=ImgDetMain&o=7&rm=3' : 'https://static9.depositphotos.com/1594920/1089/i/950/depositphotos_10893465-stock-photo-british-longhair-kitten-3-months.jpg'" 
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

                <!-- Step 4: Personality Questions (3 sets of 10 with transitions) -->
                <template x-if="currentStage === 'personality'">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">Personality Assessment</h2>
                        <p class="text-[var(--color-muted-foreground)] mb-4">Set <span x-text="currentSection + 1"></span> of <span x-text="totalSections"></span> · Questions <span x-text="(currentSection*questionsPerSection)+1"></span>-<span x-text="Math.min((currentSection+1)*questionsPerSection, personalityQuestions.length)"></span></p>
                        <p class="text-sm text-[var(--color-muted-foreground)] mb-4">All questions in this set are required.</p>

                        <!-- Overall Progress Bar -->
                        <div class="w-full rounded-full h-2.5 mb-6" style="background-color: color-mix(in oklab, var(--color-muted) 60%, white);">
                            <div class="h-2.5 rounded-full bg-[var(--color-primary)]" :style="`width: ${Math.round((answeredCount()/personalityQuestions.length)*100)}%`"></div>
                        </div>

                        <!-- Section Card with animated transition -->
                        <div class="border rounded-lg p-6 mb-6 bg-white shadow-sm overflow-hidden"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2">
                            <template x-for="(q, i) in sectionQuestions()" :key="currentSection + '-' + i">
                                <div class="mb-5">
                                    <h3 class="text-base font-medium mb-3" x-text="q.question"></h3>
                                    <div class="grid grid-cols-5 gap-2 text-center text-xs text-[var(--color-muted-foreground)] mb-1">
                                        <div>Strongly Disagree</div>
                                        <div class="col-span-3"></div>
                                        <div>Strongly Agree</div>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <template x-for="n in 5" :key="n">
                                            <label class="flex flex-col items-center cursor-pointer select-none">
                                                <input type="radio" :name="'q'+(sectionBaseIndex()+i)" :value="n"
                                                    x-model="personalityAnswers[sectionBaseIndex()+i]"
                                                    class="mb-1 h-4 w-4 accent-[var(--color-primary)] transition duration-200">
                                                <span class="text-sm" x-text="n"></span>
                                            </label>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <!-- Section completion hint -->
                            <p class="text-sm text-amber-600" x-show="attemptedNext && !isSectionComplete()"
                               x-transition:enter="transition ease-out duration-300"
                               x-transition:enter-start="opacity-0 transform translate-y-2"
                               x-transition:enter-end="opacity-100 transform translate-y-0">
                               Please answer all questions in this set before continuing.
                            </p>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                                <button class="w-full sm:w-auto text-white bg-[#24292F] hover:bg-[#24292F]/90 focus:ring-4 focus:outline-none focus:ring-[#24292F]/50 font-medium rounded-lg text-sm px-5 py-2.5" 
                                    @click="prevSet" x-show="currentSection > 0">
                                    Back
                                </button>
                                <button class="w-full sm:w-auto text-white bg-[#24292F] hover:bg-[#24292F]/90 focus:ring-4 focus:outline-none focus:ring-[#24292F]/50 font-medium rounded-lg text-sm px-5 py-2.5" 
                                    @click="currentStage = 'size'">
                                    Back to Preferences
                                </button>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-3 items-center w-full md:w-auto justify-between md:justify-end">
                                <div class="text-sm text-[var(--color-muted-foreground)] order-1 sm:order-0">Set progress: <span x-text="sectionAnsweredCount()"></span>/<span x-text="questionsPerSection"></span></div>
                                <button class="w-full sm:w-auto text-white bg-[var(--color-primary)] hover:bg-[var(--color-primary-dark)] focus:ring-4 focus:outline-none focus:ring-[var(--color-accent)] font-medium rounded-lg text-sm px-5 py-2.5" 
                                    @click="isSectionComplete() ? nextSet() : attemptedNext = true">
                                    <span x-show="currentSection < totalSections - 1">Next Set</span>
                                    <span x-show="currentSection === totalSections - 1">See Results</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Results -->
                <template x-if="currentStage === 'results'">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">Your Perfect Pet Matches</h2>
                        <p class="mb-6">Based on your personality and preferences, here are the breeds we recommend:</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <template x-for="(breed, index) in recommendedBreeds" :key="index">
                                <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition h-full flex flex-col">
                                    <img :src="getBreedImage(breed)" :alt="breed.name"
                                        class="w-full h-48 object-cover" loading="lazy">
                                    <div class="p-4 flex flex-col flex-1">
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
                                                    <span class="text-xs px-2 py-1 rounded bg-[color-mix(in_oklab,var(--color-primary)_12%,white)] text-[var(--color-primary)] border border-[var(--color-border)]" x-text="trait"></span>
                                                </template>
                                            </div>
                                        </div>
                                        
                                        <!-- Add link to view more details -->
                                        <div class="mt-auto pt-4 text-center">
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
                                <a href="{{ route('login') }}" class="text-white bg-[#050708] hover:bg-[#050708]/90 focus:ring-4 focus:outline-none focus:ring-[#050708]/50 font-semibold rounded-lg text-base px-6 py-2.5 h-[46px] items-center justify-center mr-4 inline-flex transition duration-150">
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

            // Small helper to compare preference values tolerantly (handles "longhair", "long hair", "Long", etc.)
            const matchesPref = (actual, expected) => {
                if (!expected) return false;
                const a = String(actual || '').toLowerCase().replace(/[^a-z0-9]+/g, ' ');
                const e = String(expected || '').toLowerCase().replace(/[^a-z0-9]+/g, ' ');
                if (a === e) return true;
                if (a.includes(e)) return true;
                if (e.includes(a)) return true;
                // handle singular/plural and short/long forms
                if (a.indexOf('short') !== -1 && e.indexOf('short') !== -1) return true;
                if (a.indexOf('long') !== -1 && e.indexOf('long') !== -1) return true;
                return false;
            };

            // Enforce hair-length preference: prefer breeds that explicitly match the requested hair length.
            // If any breeds explicitly match, use only those. If none explicitly match, allow all breeds
            // but apply a strong penalty to breeds that explicitly conflict with the requested hair length.
            let sourceBreeds = breeds;
            let hairConflictPenalty = 0; // if >0, apply penalty during scoring for conflicting hair lengths
            if (this.preferences && this.preferences.hairLength) {
                const requested = String(this.preferences.hairLength).toLowerCase();
                // breeds that explicitly indicate the requested hair length
                const explicitMatches = breeds.filter(b => (b.hairLength || '') !== '' && matchesPref(b.hairLength, requested));
                console.log(`Explicit hairLength matches for '${requested}': ${explicitMatches.length}`);
                if (explicitMatches.length > 0) {
                    sourceBreeds = explicitMatches;
                } else {
                    // No explicit matches: allow all breeds but penalize breeds that explicitly state the opposite
                    hairConflictPenalty = 40; // large penalty to avoid recommending obvious mismatches
                    console.log('No explicit hairLength matches; will penalize breeds whose hairLength conflicts with preference');
                }
            }
            
            // PHASE 1: Exact matches
            let exactMatches = sourceBreeds.filter(breed => 
                // tolerant matching using matchesPref
                matchesPref(breed.hairLength, this.preferences.hairLength) &&
                matchesPref(breed.size, this.preferences.size)
            );
            console.log(`✓ Exact matches: ${exactMatches.length}`);
            
            // PHASE 2: Partial matches
            let partialMatches = [];
            if (exactMatches.length < 5) {
                partialMatches = sourceBreeds.filter(breed => 
                    (matchesPref(breed.hairLength, this.preferences.hairLength) ||
                     matchesPref(breed.size, this.preferences.size)) &&
                    !exactMatches.includes(breed)
                );
                console.log(`✓ Partial matches: ${partialMatches.length}`);
            }
            
            // PHASE 3: Enhanced scoring algorithm
            const scoreBreed = (breed) => {
                let score = 0;
                let matchDetails = [];
                
                // Exact preference matches (50 points total) using tolerant matching
                if (matchesPref(breed.hairLength, this.preferences.hairLength)) {
                    score += 25;
                    matchDetails.push('✓ Hair length');
                } else if (hairConflictPenalty > 0 && (breed.hairLength || '') !== '' && !matchesPref(breed.hairLength, this.preferences.hairLength)) {
                    // Apply heavy penalty for explicit conflict when we're in fallback mode
                    score -= hairConflictPenalty;
                    matchDetails.push('✗ Hair length conflict');
                }
                if (matchesPref(breed.size, this.preferences.size)) {
                    score += 25;
                    matchDetails.push('✓ Size');
                }
                
                // Personality matching (50+ points possible)
                if (breed.personalityMatch && Array.isArray(breed.personalityMatch)) {
                    breed.personalityMatch.forEach(trait => {
                        // Binary match: 15 points each
                        if (profile[trait]) {
                            score += 15;
                            matchDetails.push(`✓ ${trait}`);
                        }
                        
                        // Proportional scoring: up to 5 additional points
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
                
                // Bonus for multiple matches
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
            
            // Score all matches
            exactMatches.forEach(breed => scoreBreed(breed));
            partialMatches.forEach(breed => {
                const baseScore = scoreBreed(breed);
                breed.matchScore = baseScore * 0.7;
            });
            
            let allCandidates = [...exactMatches, ...partialMatches];
            
            // Last resort: score all breeds
            if (allCandidates.length < 5) {
                // If we still have too few candidates, include additional breeds from the full list
                // (this allows a graceful fallback when strict hair-length filtering left too few options)
                const remaining = breeds.filter(b => !allCandidates.includes(b));
                remaining.forEach(breed => {
                    const baseScore = scoreBreed(breed);
                    breed.matchScore = baseScore * 0.5;
                });
                allCandidates = [...allCandidates, ...remaining];
            }
            
            // Sort by score
            allCandidates.sort((a, b) => b.matchScore - a.matchScore);

            // Determine dynamic selection threshold so we don't always return 3 items
            const topScore = allCandidates.length ? allCandidates[0].matchScore : 0;
            // base threshold: 70% of top score, but at least 30 (arbitrary floor)
            const dynamicThreshold = Math.max(30, topScore * 0.7);

            // If there are exact matches, prefer them and require a tighter threshold (80% of top exact score)
            let selected = [];
            if (exactMatches.length > 0) {
                exactMatches.sort((a, b) => b.matchScore - a.matchScore);
                const topExact = exactMatches[0].matchScore;
                const exactThreshold = Math.max(dynamicThreshold, topExact * 0.8);
                selected = exactMatches.filter(b => b.matchScore >= exactThreshold);
                // If no exact passes the tighter threshold, fall back to the top exact match only
                if (selected.length === 0 && exactMatches.length > 0) {
                    selected = [exactMatches[0]];
                }
            } else {
                // Otherwise select across allCandidates using the dynamic threshold
                selected = allCandidates.filter(b => b.matchScore >= dynamicThreshold);
            }

            // If selection is empty (very low scores), always include the top candidate
            if (selected.length === 0 && allCandidates.length > 0) {
                selected = [allCandidates[0]];
            }

            // Limit to at most 3 recommendations to avoid overwhelming the user
            selected = selected.slice(0, 3);

            // Debug output
            console.log('═══ SELECTED MATCHES (dynamic) ═══');
            selected.forEach((breed, i) => {
                console.log(`${i + 1}. ${breed.name} (Score: ${breed.matchScore})`);
                console.log(`   Size: ${breed.size} | Hair: ${breed.hairLength}`);
                console.log(`   Personalities: ${breed.personalityMatch?.join(', ') || 'None'}`);
                console.log(`   ${breed.matchDetails.join(' | ')}`);
            });

            this.recommendedBreeds = selected;
            
            // Ensure complete data
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
                // Map internal personality dimensions to the server-expected fields and scale to 1..7
                personalityScores: (function(dims){
                    const to7 = (score, max) => {
                        const s = Number(score) || 0;
                        const m = Number(max) || 1;
                        // scale 0..max -> 1..7
                        const scaled = Math.round((s / m) * 6) + 1;
                        return Math.min(Math.max(scaled, 1), 7);
                    };
                    return {
                        // server expects 'honesty' key (not honestyHumility)
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

                // success
                this.resultsSaved = true;
                const notification = document.createElement('div');
                notification.className = 'fixed bottom-5 right-5 bg-green-500 text-white px-6 py-3 rounded-lg shadow-xl z-50';
                notification.style.animation = 'slideIn 0.3s ease-out';
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
                    notification.style.animation = 'slideOut 0.3s ease-in';
                    setTimeout(() => notification.remove(), 300);
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

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100px);
        }
    }
`;
document.head.appendChild(style);
    </script>
    <script>
        // If a start query parameter is present (e.g. ?start=petType) make it available for the Alpine app
        // This small helper ensures the quizApp can pick it up via URLSearchParams when initializing
        // (No-op here; the main quizApp reads URLSearchParams directly.)
    </script>
@endsection
