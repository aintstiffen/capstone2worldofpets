@extends('layouts.app')

@section('content')
<div x-data="compareData()" class="min-h-screen bg-[var(--color-background)] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-[var(--color-card)] rounded-lg shadow-sm p-6 border border-[var(--color-border)]">
            <h1 class="text-3xl font-bold text-center mb-8 text-[var(--color-foreground)]">Compare Breeds</h1>

            <!-- Animal Type Selection -->
            <div class="mb-8">
                <h2 class="text-lg font-medium mb-4 text-center text-[var(--color-muted-foreground)]">Select Animal Type</h2>
                <div class="flex justify-center gap-4">
                    <button 
                        @click="setAnimalType('cat')"
                        class="px-8 py-3 rounded-lg font-medium transition-all transform hover:scale-105 hover-lift"
                        :class="animalType === 'cat' 
                            ? 'bg-[var(--color-primary)] text-white hover:bg-[var(--color-primary-dark)] shadow-lg' 
                            : 'bg-[var(--color-muted)] text-[var(--color-foreground)] hover:bg-[color-mix(in_oklab,var(--color-primary)_15%,white)]'">
                        🐱 Cats
                    </button>
                    <button 
                        @click="setAnimalType('dog')"
                        class="px-8 py-3 rounded-lg font-medium transition-all transform hover:scale-105 hover-lift"
                        :class="animalType === 'dog' 
                            ? 'bg-[var(--color-primary)] text-white hover:bg-[var(--color-primary-dark)] shadow-lg' 
                            : 'bg-[var(--color-muted)] text-[var(--color-foreground)] hover:bg-[color-mix(in_oklab,var(--color-primary)_15%,white)]'">
                        🐕 Dogs
                    </button>
                </div>
            </div>

            <!-- Loading State -->
            <div x-show="loading" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[var(--color-primary)]"></div>
                <p class="mt-4 text-lg text-[var(--color-muted-foreground)]">Loading...</p>
            </div>

            <!-- Error Message -->
            <div x-show="error" x-transition class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-sm text-red-600" x-text="error"></p>
                </div>
            </div>

            <!-- Breed Selection -->
            <div x-show="animalType && !loading && !error && breeds.length > 0" x-transition class="space-y-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="block text-sm font-semibold text-[var(--color-foreground)] mb-3">
                            <span class="flex items-center">
                                <div class="w-6 h-6 bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-primary-dark)] rounded-full flex items-center justify-center mr-3">
                                    <span class="text-white text-xs font-bold">1</span>
                                </div>
                                First Breed
                            </span>
                        </label>
                        <div class="select-container relative">
                            <select 
                                x-model="breed1"
                                class="appearance-none w-full px-4 py-4 pr-12 text-base border-2 border-[var(--color-border)] rounded-xl shadow-sm bg-white hover:border-[var(--color-primary)] focus:outline-none focus:ring-4 focus:ring-[color-mix(in_oklab,var(--color-primary)_20%,white)] focus:border-[var(--color-primary)] transition-all duration-200 text-[var(--color-foreground)] font-medium cursor-pointer">
                                <option value="" class="text-[var(--color-muted-foreground)]">🔎 Choose your first breed...</option>
                                <template x-for="breed in breeds" :key="breed.id">
                                    <option :value="breed.id" x-text="breed.name" class="py-2 text-[var(--color-foreground)]"></option>
                                </template>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                                <svg class="w-5 h-5 text-[var(--color-muted-foreground)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-sm font-semibold text-[var(--color-foreground)] mb-3">
                            <span class="flex items-center">
                                <div class="w-6 h-6 bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-primary-dark)] rounded-full flex items-center justify-center mr-3">
                                    <span class="text-white text-xs font-bold">2</span>
                                </div>
                                Second Breed
                            </span>
                        </label>
                        <div class="select-container relative">
                            <select 
                                x-model="breed2"
                                class="appearance-none w-full px-4 py-4 pr-12 text-base border-2 border-[var(--color-border)] rounded-xl shadow-sm bg-white hover:border-[var(--color-primary)] focus:outline-none focus:ring-4 focus:ring-[color-mix(in_oklab,var(--color-primary)_20%,white)] focus:border-[var(--color-primary)] transition-all duration-200 text-[var(--color-foreground)] font-medium cursor-pointer">
                                <option value="" class="text-[var(--color-muted-foreground)]">🔎 Choose your second breed...</option>
                                <template x-for="breed in breeds" :key="breed.id">
                                    <option :value="breed.id" x-text="breed.name" class="py-2 text-[var(--color-foreground)]"></option>
                                </template>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                                <svg class="w-5 h-5 text-[var(--color-muted-foreground)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Compare Button -->
                <div x-show="breed1 && breed2" x-transition class="text-center">
                    <button 
                        @click="compare"
                        :disabled="loading"
                        class="inline-flex items-center px-8 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg x-show="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Compare Breeds
                    </button>
                </div>
            </div>

            <!-- No breeds message -->
            <div x-show="animalType && !loading && !error && breeds.length === 0" x-transition class="text-center py-8">
                <p class="text-[var(--color-muted-foreground)]">No breeds found for the selected animal type.</p>
            </div>

            <!-- Recent Comparisons -->
            <template x-if="getRecentComparisons().length > 0">
                <div class="mt-8 bg-[var(--color-muted)] rounded-lg p-6 border border-[var(--color-border)]">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-[var(--color-foreground)]">Recent Comparisons</h3>
                        <button @click="clearRecentComparisons(); refreshRecentComparisons()" class="text-sm text-red-600 hover:text-red-700 underline">
                            Clear All
                        </button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <template x-for="recent in getRecentComparisons().slice(0, 6)" :key="recent.id">
                            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow cursor-pointer" 
                                 @click="loadRecentComparison(recent)">
                                <div class="flex items-center space-x-3 mb-2">
                                    <span class="text-sm font-medium text-gray-600 capitalize" x-text="recent.animalType"></span>
                                    <span class="text-xs text-gray-400" x-text="new Date(recent.timestamp).toLocaleDateString()"></span>
                                </div>
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- Breed 1 -->
                                    <div class="text-center">
                                        <img :src="recent.breed1Image" :alt="recent.breed1Name" class="w-12 h-12 object-cover rounded-lg mx-auto mb-1" onerror="this.style.display='none'">
                                        <p class="text-xs text-gray-700 truncate" x-text="recent.breed1Name"></p>
                                    </div>
                                    <!-- VS -->
                                    <div class="text-xs font-bold text-gray-500 px-2">VS</div>
                                    <!-- Breed 2 -->
                                    <div class="text-center">
                                        <img :src="recent.breed2Image" :alt="recent.breed2Name" class="w-12 h-12 object-cover rounded-lg mx-auto mb-1" onerror="this.style.display='none'">
                                        <p class="text-xs text-gray-700 truncate" x-text="recent.breed2Name"></p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- Comparison Results -->
            <template x-if="comparison && !loading">
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-center mb-8 text-[var(--color-foreground)]">Comparison Results</h2>
                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- First Breed -->
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition-shadow">
                            <div class="h-64 bg-gradient-to-br from-gray-100 to-gray-200">
                                <template x-if="comparison.breed1.image?.url">
                                    <img 
                                        :src="comparison.breed1.image.url" 
                                        :alt="comparison.breed1.info?.name"
                                        class="w-full h-full object-cover">
                                </template>
                                <template x-if="!comparison.breed1.image?.url">
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </template>
                            </div>
                            <div class="p-6 space-y-4">
                                <h3 class="text-xl font-bold text-[var(--color-foreground)]" x-text="comparison.breed1.info?.name || 'Unknown'"></h3>
                                <div class="space-y-3 text-sm">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-[var(--color-foreground)]">Temperament:</span>
                                        <span class="text-[var(--color-muted-foreground)]" x-text="comparison.breed1.info?.temperament || 'Not specified'"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-semibold text-[var(--color-foreground)]">Weight:</span>
                                        <span class="text-[var(--color-muted-foreground)]" x-text="(comparison.breed1.info?.weight?.metric || 'Not specified') + (comparison.breed1.info?.weight?.metric ? ' kg' : '')"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-semibold text-[var(--color-foreground)]">Life Span:</span>
                                        <span class="text-[var(--color-muted-foreground)]" x-text="(comparison.breed1.info?.life_span || 'Not specified') + (comparison.breed1.info?.life_span ? ' years' : '')"></span>
                                    </div>
                                    
                                    <!-- Cat-specific fields -->
                                    <template x-if="animalType === 'cat'">
                                        <div class="space-y-3 border-t pt-3">
                                            <div class="flex justify-between">
                                                <span class="font-semibold text-gray-700">Origin:</span>
                                                <span class="text-gray-600" x-text="comparison.breed1.info?.origin || 'Not specified'"></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="font-semibold text-gray-700">Intelligence:</span>
                                                <span class="text-gray-600" x-text="comparison.breed1.info?.intelligence ? comparison.breed1.info.intelligence + '/5' : 'Not specified'"></span>
                                            </div>
                                            <template x-if="comparison.breed1.info?.energy_level">
                                                <div class="flex justify-between">
                                                    <span class="font-semibold text-gray-700">Energy Level:</span>
                                                    <span class="text-gray-600" x-text="comparison.breed1.info.energy_level + '/5'"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                    
                                    <!-- Dog-specific fields -->
                                    <template x-if="animalType === 'dog'">
                                        <div class="space-y-3 border-t pt-3">
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-gray-700">Breed Group:</span>
                                                <span class="text-gray-600" x-text="comparison.breed1.info?.breed_group || 'Not specified'"></span>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-gray-700">Bred For:</span>
                                                <span class="text-gray-600" x-text="comparison.breed1.info?.bred_for || 'Not specified'"></span>
                                            </div>
                                            <template x-if="comparison.breed1.info?.height">
                                                <div class="flex justify-between">
                                                    <span class="font-semibold text-gray-700">Height:</span>
                                                    <span class="text-gray-600" x-text="(comparison.breed1.info.height.metric || 'Not specified') + (comparison.breed1.info.height.metric ? ' cm' : '')"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Second Breed -->
                        <div class="bg-[var(--color-card)] rounded-lg shadow-lg overflow-hidden border border-[var(--color-border)] hover:shadow-xl transition-shadow">
                            <div class="h-64 bg-gradient-to-br from-[color-mix(in_oklab,var(--color-primary)_5%,white)] to-[color-mix(in_oklab,var(--color-primary)_15%,white)]">
                                <template x-if="comparison.breed2.image?.url">
                                    <img 
                                        :src="comparison.breed2.image.url" 
                                        :alt="comparison.breed2.info?.name"
                                        class="w-full h-full object-cover">
                                </template>
                                <template x-if="!comparison.breed2.image?.url">
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </template>
                            </div>
                            <div class="p-6 space-y-4">
                                <h3 class="text-xl font-bold text-[var(--color-foreground)]" x-text="comparison.breed2.info?.name || 'Unknown'"></h3>
                                <div class="space-y-3 text-sm">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-[var(--color-foreground)]">Temperament:</span>
                                        <span class="text-[var(--color-muted-foreground)]" x-text="comparison.breed2.info?.temperament || 'Not specified'"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-semibold text-[var(--color-foreground)]">Weight:</span>
                                        <span class="text-[var(--color-muted-foreground)]" x-text="(comparison.breed2.info?.weight?.metric || 'Not specified') + (comparison.breed2.info?.weight?.metric ? ' kg' : '')"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-semibold text-[var(--color-foreground)]">Life Span:</span>
                                        <span class="text-[var(--color-muted-foreground)]" x-text="(comparison.breed2.info?.life_span || 'Not specified') + (comparison.breed2.info?.life_span ? ' years' : '')"></span>
                                    </div>
                                    
                                    <!-- Cat-specific fields -->
                                    <template x-if="animalType === 'cat'">
                                        <div class="space-y-3 border-t pt-3">
                                            <div class="flex justify-between">
                                                <span class="font-semibold text-gray-700">Origin:</span>
                                                <span class="text-gray-600" x-text="comparison.breed2.info?.origin || 'Not specified'"></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="font-semibold text-gray-700">Intelligence:</span>
                                                <span class="text-gray-600" x-text="comparison.breed2.info?.intelligence ? comparison.breed2.info.intelligence + '/5' : 'Not specified'"></span>
                                            </div>
                                            <template x-if="comparison.breed2.info?.energy_level">
                                                <div class="flex justify-between">
                                                    <span class="font-semibold text-gray-700">Energy Level:</span>
                                                    <span class="text-gray-600" x-text="comparison.breed2.info.energy_level + '/5'"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                    
                                    <!-- Dog-specific fields -->
                                    <template x-if="animalType === 'dog'">
                                        <div class="space-y-3 border-t pt-3">
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-gray-700">Breed Group:</span>
                                                <span class="text-gray-600" x-text="comparison.breed2.info?.breed_group || 'Not specified'"></span>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-gray-700">Bred For:</span>
                                                <span class="text-gray-600" x-text="comparison.breed2.info?.bred_for || 'Not specified'"></span>
                                            </div>
                                            <template x-if="comparison.breed2.info?.height">
                                                <div class="flex justify-between">
                                                    <span class="font-semibold text-gray-700">Height:</span>
                                                    <span class="text-gray-600" x-text="(comparison.breed2.info.height.metric || 'Not specified') + (comparison.breed2.info.height.metric ? ' cm' : '')"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reset Button -->
                    <div class="text-center mt-8">
                        <button 
                            @click="comparison = null; breed1 = ''; breed2 = '';"
                            class="inline-flex items-center px-6 py-2 border border-[var(--color-border)] rounded-lg shadow-sm text-base font-medium text-[var(--color-foreground)] bg-white hover:bg-[var(--color-muted)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)] transition-colors hover-lift">
                            Compare Different Breeds
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function compareData() {
    return {
        animalType: '',
        breeds: [],
        breed1: '',
        breed2: '',
        comparison: null,
        loading: false,
        error: null,
        recentComparisons: [],

        init() {
            // Load recent comparisons into reactive data
            this.refreshRecentComparisons();
            
            // Initialize with any URL parameters for loading recent comparisons
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('type') && urlParams.get('breed1') && urlParams.get('breed2')) {
                this.animalType = urlParams.get('type');
                this.breed1 = urlParams.get('breed1');
                this.breed2 = urlParams.get('breed2');
                this.loadBreeds();
            }
        },

        async setAnimalType(type) {
            this.animalType = type;
            this.breed1 = '';
            this.breed2 = '';
            this.comparison = null;
            this.error = null;
            this.loading = true;
            
            try {
                const response = await fetch(`/compare/breeds/${type}`);
                if (!response.ok) throw new Error('Failed to load breeds');
                this.breeds = await response.json();
            } catch (e) {
                this.error = 'Failed to load breeds. Please try again.';
                console.error(e);
            }
            
            this.loading = false;
        },

        async compare() {
            if (!this.breed1 || !this.breed2) return;
            
            this.loading = true;
            this.error = null;
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                const response = await fetch('/compare', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken ? csrfToken.content : ''
                    },
                    body: JSON.stringify({
                        type: this.animalType,
                        breed1: this.breed1,
                        breed2: this.breed2
                    })
                });
                
                if (!response.ok) throw new Error('Failed to compare breeds');
                this.comparison = await response.json();
                
                // Save comparison to localStorage
                this.saveComparisonToLocal();
            } catch (e) {
                this.error = 'Failed to compare breeds. Please try again.';
                console.error(e);
            }
            
            this.loading = false;
        },
        
        saveComparisonToLocal() {
            if (!this.comparison) return;
            
            const comparisonData = {
                id: Date.now(), // Simple unique ID
                animalType: this.animalType,
                breed1: this.breed1,
                breed2: this.breed2,
                breed1Name: this.comparison.breed1.info.name,
                breed2Name: this.comparison.breed2.info.name,
                breed1Image: this.comparison.breed1.image,
                breed2Image: this.comparison.breed2.image,
                comparisonData: this.comparison,
                timestamp: new Date().toISOString()
            };
            
            // Get existing comparisons or initialize empty array (user-specific)
            const storageKey = this.getUserStorageKey();
            let recentComparisons = JSON.parse(localStorage.getItem(storageKey) || '[]');
            
            // Add new comparison to the beginning
            recentComparisons.unshift(comparisonData);
            
            // Keep only last 10 comparisons
            recentComparisons = recentComparisons.slice(0, 10);
            
            // Save back to localStorage (user-specific)
            localStorage.setItem(storageKey, JSON.stringify(recentComparisons));
            
            // Refresh reactive data
            this.refreshRecentComparisons();
        },
        
        getRecentComparisons() {
            return this.recentComparisons;
        },
        
        refreshRecentComparisons() {
            const storageKey = this.getUserStorageKey();
            this.recentComparisons = JSON.parse(localStorage.getItem(storageKey) || '[]');
        },
        
        async loadBreeds() {
            if (!this.animalType) return;
            
            this.loading = true;
            try {
                const response = await fetch(`/compare/breeds/${this.animalType}`);
                if (!response.ok) throw new Error('Failed to load breeds');
                this.breeds = await response.json();
            } catch (e) {
                this.error = 'Failed to load breeds. Please try again.';
                console.error(e);
            }
            this.loading = false;
        },
        
        loadRecentComparison(comparisonData) {
            this.animalType = comparisonData.animalType;
            this.breed1 = comparisonData.breed1;
            this.breed2 = comparisonData.breed2;
            this.comparison = comparisonData.comparisonData;
            
            // Load breeds for the selected animal type
            this.loadBreeds();
        },
        
        clearRecentComparisons() {
            const storageKey = this.getUserStorageKey();
            localStorage.removeItem(storageKey);
            this.refreshRecentComparisons();
        },
        
        getUserStorageKey() {
            // Get user ID from meta tag or default to 'guest'
            const userIdMeta = document.querySelector('meta[name="user-id"]');
            const userId = userIdMeta ? userIdMeta.content : 'guest';
            return `recentComparisons_${userId}`;
        }
    }
}
</script>
@endpush

@push('styles')
<style>
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Enhanced dropdown styling */
select {
    background-image: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}

select:focus {
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    transform: translateY(-1px);
}

select:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Custom option styling */
select option {
    padding: 12px 16px;
    background: white;
    color: #374151;
    font-weight: 500;
}

select option:hover {
    background: #f3f4f6;
}

select option:checked {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
}

/* Custom scrollbar for select elements */
select::-webkit-scrollbar {
    width: 8px;
}

select::-webkit-scrollbar-track {
    background: #f8fafc;
    border-radius: 10px;
}

select::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
    border-radius: 10px;
    border: 1px solid #f1f5f9;
}

select::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #cbd5e1, #94a3b8);
}

/* Floating label effect */
.select-container {
    position: relative;
}

.select-container label {
    transition: all 0.3s ease;
}

/* Dropdown animation */
select {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Focus ring enhancement */
select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 
        0 0 0 4px rgba(59, 130, 246, 0.1),
        0 4px 12px rgba(0, 0, 0, 0.05);
}

/* Hover effects */
.select-container:hover select {
    border-color: #6b7280;
}

/* Custom arrow styling */
.select-container svg {
    transition: transform 0.2s ease;
}

select:focus + div svg,
.select-container:hover svg {
    transform: rotate(180deg);
    color: #3b82f6;
}

/* Mobile responsive */
@media (max-width: 768px) {
    select {
        padding: 16px 12px;
        font-size: 16px; /* Prevents zoom on iOS */
    }
}

/* Gradient borders for enhanced look */
.select-container::before {
    content: '';
    position: absolute;
    inset: 0;
    padding: 2px;
    background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
    border-radius: 12px;
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    mask-composite: exclude;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.select-container:hover::before {
    opacity: 1;
}
</style>
@endpush