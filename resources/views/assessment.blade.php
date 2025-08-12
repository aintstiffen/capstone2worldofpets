@extends('layouts.app')

@section('title', 'Personality Assessment')

@section('content')
<div 
    class="flex flex-col min-h-screen"
    x-data="quizApp()"
>
    <!-- Header -->
    <header class="bg-white border-b sticky top-0 z-10">
        <div class="container flex items-center justify-between h-16 px-4 md:px-6">
            <a href="" class="flex items-center gap-2 font-bold text-xl">
                🐾 World of Pets
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        <div class="container py-8 max-w-3xl mx-auto">

            <!-- Step 1: Choose pet type -->
            <template x-if="!petType">
                <div>
                    <h2 class="text-2xl font-bold mb-4">Choose your preferred pet type</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <button 
                            class="p-6 border rounded-lg hover:bg-gray-50"
                            @click="petType = 'dog'"
                        >🐶 Dog</button>
                        <button 
                            class="p-6 border rounded-lg hover:bg-gray-50"
                            @click="petType = 'cat'"
                        >🐱 Cat</button>
                    </div>
                </div>
            </template>

            <!-- Step 2: Questions -->
            <template x-if="petType && !showResults">
                <div>
                    <h2 class="text-2xl font-bold mb-4">Question <span x-text="currentStep + 1"></span> of <span x-text="questions.length"></span></h2>

                    <!-- Progress Bar -->
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                        <div class="bg-blue-500 h-2.5 rounded-full" 
                             :style="`width: ${((currentStep+1)/questions.length)*100}%`">
                        </div>
                    </div>

                    <div class="border rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4" x-text="questions[currentStep].text"></h3>
                        <div class="space-y-2">
                            <template x-for="option in questions[currentStep].options" :key="option.value">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" 
                                           :name="'q' + currentStep"
                                           :value="option.value"
                                           x-model="answers[currentStep]">
                                    <span x-text="option.label"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div class="flex justify-between mt-4">
                        <button 
                            class="px-4 py-2 border rounded" 
                            @click="prevStep"
                            x-show="currentStep > 0"
                        >← Back</button>
                        <button 
                            class="px-4 py-2 bg-blue-500 text-white rounded" 
                            @click="nextStep"
                            :disabled="!answers[currentStep]"
                        >
                            <span x-show="currentStep < questions.length - 1">Next →</span>
                            <span x-show="currentStep === questions.length - 1">See Results</span>
                        </button>
                    </div>
                </div>
            </template>

            <!-- Step 3: Results -->
            <template x-if="showResults">
                <div>
                    <h2 class="text-2xl font-bold mb-4">Your Recommended Breeds</h2>
                    <p class="mb-6">Based on your answers, here are some breeds you might like:</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <template x-for="breed in recommendedBreeds" :key="breed.name">
                            <div class="border rounded-lg p-4">
                                <img :src="breed.image" alt="" class="w-full h-40 object-cover rounded mb-2">
                                <h3 class="font-bold text-lg" x-text="breed.name"></h3>
                                <p class="text-sm text-gray-600" x-text="breed.description"></p>
                            </div>
                        </template>
                    </div>

                    <button 
                        class="mt-6 px-4 py-2 bg-blue-500 text-white rounded"
                        @click="restart"
                    >Start Over</button>
                </div>
            </template>

        </div>
    </main>
</div>

<!-- Alpine.js State & Logic -->
<script>
function quizApp() {
    return {
        petType: null,
        currentStep: 0,
        answers: {},
        showResults: false,
        questions: [
            { text: "How active are you?", options: [
                { label: "Very active", value: "active" },
                { label: "Moderately active", value: "moderate" },
                { label: "Prefer calm activities", value: "calm" }
            ]},
            { text: "How much space do you have?", options: [
                { label: "Large yard", value: "large" },
                { label: "Apartment", value: "small" },
                { label: "House with small yard", value: "medium" }
            ]},
            { text: "How much grooming are you okay with?", options: [
                { label: "High maintenance", value: "high" },
                { label: "Low maintenance", value: "low" },
                { label: "Doesn't matter", value: "any" }
            ]},
        ],
        recommendedBreeds: [],
        nextStep() {
            if (this.currentStep < this.questions.length - 1) {
                this.currentStep++;
            } else {
                this.calculateResults();
                this.showResults = true;
            }
        },
        prevStep() {
            if (this.currentStep > 0) {
                this.currentStep--;
            }
        },
        calculateResults() {
            // In real app, fetch from backend based on answers + petType
            if (this.petType === 'dog') {
                this.recommendedBreeds = [
                    { name: "Golden Retriever", image: "https://placedog.net/400/300?id=1", description: "Friendly, active, and loyal." },
                    { name: "Shiba Inu", image: "https://placedog.net/400/300?id=2", description: "Independent and alert." }
                ];
            } else {
                this.recommendedBreeds = [
                    { name: "Siamese", image: "https://placekitten.com/400/300", description: "Social, intelligent, and vocal." },
                    { name: "Persian", image: "https://placekitten.com/401/300", description: "Calm, affectionate, and gentle." }
                ];
            }
        },
        restart() {
            this.petType = null;
            this.currentStep = 0;
            this.answers = {};
            this.showResults = false;
            this.recommendedBreeds = [];
        }
    }
}
</script>
@endsection
