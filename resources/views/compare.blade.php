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
                                <option value="" class="text-[var(--color-muted-foreground)]">� Choose your first breed...</option>
                                <template x-for="breed in breeds" :key="breed.id">
                                    <option :value="breed.id" x-text="breed.name" class="py-2 text-[var(--color-foreground]"></option>
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
                                <option value="" class="text-[var(--color-muted-foreground)]">� Choose your second breed...</option>
                                <template x-for="breed in breeds" :key="breed.id">
                                    <option :value="breed.id" x-text="breed.name" class="py-2 text-[var(--color-foreground]"></option>
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
                            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow cursor-pointer recent-card animate-card" 
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
                                <span class="paw-icon">🐾</span>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- Comparison Results -->
            <template x-if="comparison && !loading">
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-center mb-8 text-[var(--color-foreground)]">Comparison Results</h2>
                    
                    <!-- Compatibility Overview -->
                    <div class="compatibility-comparison mb-8">
                        <div class="text-center mb-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Breed Compatibility Score</h3>
                            <p class="text-sm text-gray-600 mb-4">This score shows how similar these breeds are based on temperament, size, energy level, and other characteristics. Higher scores mean the breeds share more common traits.</p>
                        </div>
                        
                        <div class="max-w-2xl mx-auto">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm font-semibold text-gray-700" x-text="comparison.breed1.info?.name"></span>
                                <div class="compatibility-badge-md" x-text="calculateCompatibility(comparison.breed1, comparison.breed2) + '%'"></div>
                                <span class="text-sm font-semibold text-gray-700" x-text="comparison.breed2.info?.name"></span>
                            </div>
                            <div class="compatibility-bar">
                                <div class="compatibility-fill" :style="`width: ${calculateCompatibility(comparison.breed1, comparison.breed2)}%`"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-3 text-center">
                                <span x-show="calculateCompatibility(comparison.breed1, comparison.breed2) >= 80">✨ These breeds are very similar! They share many common characteristics.</span>
                                <span x-show="calculateCompatibility(comparison.breed1, comparison.breed2) >= 60 && calculateCompatibility(comparison.breed1, comparison.breed2) < 80">👍 These breeds have moderate similarity with some shared traits.</span>
                                <span x-show="calculateCompatibility(comparison.breed1, comparison.breed2) >= 40 && calculateCompatibility(comparison.breed1, comparison.breed2) < 60">📊 These breeds have some differences but also share certain characteristics.</span>
                                <span x-show="calculateCompatibility(comparison.breed1, comparison.breed2) < 40">🔍 These breeds are quite different with unique characteristics that set them apart.</span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- First Breed -->
                        <div class="breed-result-card animate-in group relative">
                            <div class="overflow-hidden h-64 bg-gradient-to-br from-gray-100 to-gray-200 cursor-pointer" @click="$dispatch('show-image', { url: comparison.breed1.image?.url, name: comparison.breed1.info?.name })">
                                <template x-if="comparison.breed1.image?.url">
                                    <img 
                                        :src="comparison.breed1.image.url" 
                                        :alt="comparison.breed1.info?.name"
                                        class="breed-image w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                </template>
                                <template x-if="!comparison.breed1.image?.url">
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </template>
                            </div>
                            <div class="mt-5 p-6">
                                <h3 class="text-2xl font-bold text-gray-800 mb-2" x-text="comparison.breed1.info?.name || 'Unknown'"></h3>
                                <div class="space-y-3 text-sm">
                                    <div class="info-card-md">
                                        <div class="flex items-start gap-2 mb-2">
                                            <span class="text-lg">🎭</span>
                                            <div class="flex-1">
                                                <span class="font-bold text-gray-800 block mb-1">Temperament (Personality)</span>
                                                <span class="text-gray-700 block mb-2" x-text="comparison.breed1.info?.temperament || 'Not specified'"></span>
                                                <span class="text-xs text-gray-500 italic">This describes the breed's typical personality traits and how they usually behave. Think of it as their natural character - whether they're playful, calm, friendly with strangers, or prefer quiet time with family.</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-card-md">
                                        <div class="flex items-start gap-2 mb-2">
                                            <span class="text-lg">⚖️</span>
                                            <div class="flex-1">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="font-bold text-gray-800">Weight</span>
                                                    <span class="text-gray-700 font-semibold" x-text="(comparison.breed1.info?.weight?.metric || 'Not specified') + (comparison.breed1.info?.weight?.metric ? ' kg' : '')"></span>
                                                </div>
                                                <span class="text-xs text-gray-500 italic">The typical weight range for a healthy adult of this breed. This helps you understand how big they'll grow and how much space they need. Lighter breeds are easier to carry, while heavier breeds may need more food and exercise.</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-card-md">
                                        <div class="flex items-start gap-2 mb-2">
                                            <span class="text-lg">🕐</span>
                                            <div class="flex-1">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="font-bold text-gray-800">Life Span</span>
                                                    <span class="text-gray-700 font-semibold" x-text="(comparison.breed1.info?.life_span || 'Not specified') + (comparison.breed1.info?.life_span ? ' years' : '')"></span>
                                                </div>
                                                <span class="text-xs text-gray-500 italic">The average number of years this breed typically lives with proper care. This is a long-term commitment! Good nutrition, regular vet visits, and exercise can help your pet live a full, healthy life within this range.</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Cat-specific fields -->
                                    <template x-if="animalType === 'cat'">
                                        <div class="space-y-3 mt-3">
                                            <div class="info-card-md">
                                                <div class="flex items-start gap-2 mb-2">
                                                    <span class="text-lg">🌍</span>
                                                    <div class="flex-1">
                                                        <div class="flex justify-between items-center mb-1">
                                                            <span class="font-bold text-gray-800">Origin (Birthplace)</span>
                                                            <span class="text-gray-700 font-semibold" x-text="comparison.breed1.info?.origin || 'Not specified'"></span>
                                                        </div>
                                                        <span class="text-xs text-gray-500 italic">The country or region where this breed was first developed. Different climates and cultures created breeds with unique features - like thick coats for cold regions or sleek builds for warmer areas.</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="info-card-md">
                                                <div class="flex items-start gap-2 mb-2">
                                                    <span class="text-lg">🧠</span>
                                                    <div class="flex-1">
                                                        <div class="flex justify-between items-center mb-1">
                                                            <span class="font-bold text-gray-800">Intelligence (Smarts)</span>
                                                            <span class="text-gray-700 font-semibold" x-text="comparison.breed1.info?.intelligence ? comparison.breed1.info.intelligence + '/5' : 'Not specified'"></span>
                                                        </div>
                                                        <span class="text-xs text-gray-500 italic">How quickly this breed learns new things and solves problems, rated 1-5 stars. Higher scores mean they're easier to train and love puzzle toys, but may also get bored easily and need mental stimulation!</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <template x-if="comparison.breed1.info?.energy_level">
                                                <div class="info-card-md">
                                                    <div class="flex items-start gap-2 mb-2">
                                                        <span class="text-lg">⚡</span>
                                                        <div class="flex-1">
                                                            <div class="flex justify-between items-center mb-1">
                                                                <span class="font-bold text-gray-800">Energy Level (Activity)</span>
                                                                <span class="text-gray-700 font-semibold" x-text="comparison.breed1.info.energy_level + '/5'"></span>
                                                            </div>
                                                            <span class="text-xs text-gray-500 italic">How active and playful this breed is, rated 1-5 stars. High energy cats need lots of playtime and toys. Low energy cats are more relaxed and enjoy calm environments. Match this to your lifestyle!</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                    <!-- Dog-specific fields -->
                                    <template x-if="animalType === 'dog'">
                                        <div class="space-y-3 mt-3">
                                            <div class="info-card-md">
                                                <div class="flex items-start gap-2 mb-2">
                                                    <span class="text-lg">🏆</span>
                                                    <div class="flex-1">
                                                        <span class="font-bold text-gray-800 block mb-1">Breed Group (Job Category)</span>
                                                        <span class="text-gray-700 block mb-2" x-text="comparison.breed1.info?.breed_group || 'Not specified'"></span>
                                                        <span class="text-xs text-gray-500 italic">The category based on what job this breed was originally created to do. For example: Herding dogs help with livestock, Sporting dogs assist hunters, Toy dogs are companions. This tells you their natural instincts and tendencies!</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="info-card-md">
                                                <div class="flex items-start gap-2 mb-2">
                                                    <span class="text-lg">💼</span>
                                                    <div class="flex-1">
                                                        <span class="font-bold text-gray-800 block mb-1">Bred For (Original Purpose)</span>
                                                        <span class="text-gray-700 block mb-2" x-text="comparison.breed1.info?.bred_for || 'Not specified'"></span>
                                                        <span class="text-xs text-gray-500 italic">The specific task or job this breed was originally developed to perform. Understanding their history helps you know what activities they'll enjoy and what training challenges you might face. Dogs often still have these instincts today!</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <template x-if="comparison.breed1.info?.height">
                                                <div class="info-card-md">
                                                    <div class="flex items-start gap-2 mb-2">
                                                        <span class="text-lg">📏</span>
                                                        <div class="flex-1">
                                                            <div class="flex justify-between items-center mb-1">
                                                                <span class="font-bold text-gray-800">Height (Shoulder to Ground)</span>
                                                                <span class="text-gray-700 font-semibold" x-text="(comparison.breed1.info.height.metric || 'Not specified') + (comparison.breed1.info.height.metric ? ' cm' : '')"></span>
                                                            </div>
                                                            <span class="text-xs text-gray-500 italic">How tall the dog typically stands from paw to shoulder when fully grown. This helps you understand if they'll fit comfortably in your home and vehicle. Smaller dogs need less space, while larger dogs need more room to move.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                                
                                <!-- GIF Button -->
                                <div class="mt-6 pt-4 border-t border-gray-200">
                                    <button 
                                        class="compare-btn btn-bounce w-full px-4 py-3 rounded-lg font-semibold"
                                        @click="showGifModal('breed1')"
                                        x-show="comparison.breed1"
                                    >
                                        🎬 View Fun GIF
                                    </button>
                                </div>
                            </div>
                            
                            <span class="paw-icon absolute right-4 top-4">🐾</span>

                            <!-- Modal -->
                            <div 
                                x-show="gifModalVisible && gifModalBreed === 'breed1'" 
                                style="display: none;" 
                                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                                @click.self="gifModalVisible = false"
                            >
                                <div 
                                    class="bg-white rounded-lg p-6 shadow-lg max-w-lg w-full relative"
                                    @click.stop
                                >
                                    <button 
                                        class="image-popup-close absolute top-2 right-2"
                                        @click="gifModalVisible = false"
                                    >Close</button>
                                    <div class="flex flex-col items-center">
                                        <template x-if="gifUrl">
                                            <img :src="gifUrl" alt="Fun GIF" class="mb-4 rounded-lg max-h-80">
                                        </template>
                                        <template x-if="!gifUrl">
                                            <div class="text-gray-500">Loading GIF...</div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Second Breed -->
                        <div class="breed-result-card animate-in group relative">
                            <div class="overflow-hidden h-64 bg-gradient-to-br from-gray-100 to-gray-200 cursor-pointer" @click="$dispatch('show-image', { url: comparison.breed2.image?.url, name: comparison.breed2.info?.name })">
                                <template x-if="comparison.breed2.image?.url">
                                    <img 
                                        :src="comparison.breed2.image.url" 
                                        :alt="comparison.breed2.info?.name"
                                        class="breed-image w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                </template>
                                <template x-if="!comparison.breed2.image?.url">
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </template>
                            </div>
                            <div class="mt-5 p-6">
                                <h3 class="text-2xl font-bold text-gray-800 mb-2" x-text="comparison.breed2.info?.name || 'Unknown'"></h3>
                                <div class="space-y-3 text-sm">
                                    <div class="info-card-md">
                                        <div class="flex items-start gap-2 mb-2">
                                            <span class="text-lg">🎭</span>
                                            <div class="flex-1">
                                                <span class="font-bold text-gray-800 block mb-1">Temperament (Personality)</span>
                                                <span class="text-gray-700 block mb-2" x-text="comparison.breed2.info?.temperament || 'Not specified'"></span>
                                                <span class="text-xs text-gray-500 italic">This describes the breed's typical personality traits and how they usually behave. Think of it as their natural character - whether they're playful, calm, friendly with strangers, or prefer quiet time with family.</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-card-md">
                                        <div class="flex items-start gap-2 mb-2">
                                            <span class="text-lg">⚖️</span>
                                            <div class="flex-1">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="font-bold text-gray-800">Weight</span>
                                                    <span class="text-gray-700 font-semibold" x-text="(comparison.breed2.info?.weight?.metric || 'Not specified') + (comparison.breed2.info?.weight?.metric ? ' kg' : '')"></span>
                                                </div>
                                                <span class="text-xs text-gray-500 italic">The typical weight range for a healthy adult of this breed. This helps you understand how big they'll grow and how much space they need. Lighter breeds are easier to carry, while heavier breeds may need more food and exercise.</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-card-md">
                                        <div class="flex items-start gap-2 mb-2">
                                            <span class="text-lg">🕐</span>
                                            <div class="flex-1">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="font-bold text-gray-800">Life Span</span>
                                                    <span class="text-gray-700 font-semibold" x-text="(comparison.breed2.info?.life_span || 'Not specified') + (comparison.breed2.info?.life_span ? ' years' : '')"></span>
                                                </div>
                                                <span class="text-xs text-gray-500 italic">The average number of years this breed typically lives with proper care. This is a long-term commitment! Good nutrition, regular vet visits, and exercise can help your pet live a full, healthy life within this range.</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Cat-specific fields -->
                                    <template x-if="animalType === 'cat'">
                                        <div class="space-y-3 mt-3">
                                            <div class="info-card-md">
                                                <div class="flex items-start gap-2 mb-2">
                                                    <span class="text-lg">🌍</span>
                                                    <div class="flex-1">
                                                        <div class="flex justify-between items-center mb-1">
                                                            <span class="font-bold text-gray-800">Origin (Birthplace)</span>
                                                            <span class="text-gray-700 font-semibold" x-text="comparison.breed2.info?.origin || 'Not specified'"></span>
                                                        </div>
                                                        <span class="text-xs text-gray-500 italic">The country or region where this breed was first developed. Different climates and cultures created breeds with unique features - like thick coats for cold regions or sleek builds for warmer areas.</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="info-card-md">
                                                <div class="flex items-start gap-2 mb-2">
                                                    <span class="text-lg">🧠</span>
                                                    <div class="flex-1">
                                                        <div class="flex justify-between items-center mb-1">
                                                            <span class="font-bold text-gray-800">Intelligence (Smarts)</span>
                                                            <span class="text-gray-700 font-semibold" x-text="comparison.breed2.info?.intelligence ? comparison.breed2.info.intelligence + '/5' : 'Not specified'"></span>
                                                        </div>
                                                        <span class="text-xs text-gray-500 italic">How quickly this breed learns new things and solves problems, rated 1-5 stars. Higher scores mean they're easier to train and love puzzle toys, but may also get bored easily and need mental stimulation!</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <template x-if="comparison.breed2.info?.energy_level">
                                                <div class="info-card-md">
                                                    <div class="flex items-start gap-2 mb-2">
                                                        <span class="text-lg">⚡</span>
                                                        <div class="flex-1">
                                                            <div class="flex justify-between items-center mb-1">
                                                                <span class="font-bold text-gray-800">Energy Level (Activity)</span>
                                                                <span class="text-gray-700 font-semibold" x-text="comparison.breed2.info.energy_level + '/5'"></span>
                                                            </div>
                                                            <span class="text-xs text-gray-500 italic">How active and playful this breed is, rated 1-5 stars. High energy cats need lots of playtime and toys. Low energy cats are more relaxed and enjoy calm environments. Match this to your lifestyle!</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                    <!-- Dog-specific fields -->
                                    <template x-if="animalType === 'dog'">
                                        <div class="space-y-3 mt-3">
                                            <div class="info-card-md">
                                                <div class="flex items-start gap-2 mb-2">
                                                    <span class="text-lg">🏆</span>
                                                    <div class="flex-1">
                                                        <span class="font-bold text-gray-800 block mb-1">Breed Group (Job Category)</span>
                                                        <span class="text-gray-700 block mb-2" x-text="comparison.breed2.info?.breed_group || 'Not specified'"></span>
                                                        <span class="text-xs text-gray-500 italic">The category based on what job this breed was originally created to do. For example: Herding dogs help with livestock, Sporting dogs assist hunters, Toy dogs are companions. This tells you their natural instincts and tendencies!</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="info-card-md">
                                                <div class="flex items-start gap-2 mb-2">
                                                    <span class="text-lg">💼</span>
                                                    <div class="flex-1">
                                                        <span class="font-bold text-gray-800 block mb-1">Bred For (Original Purpose)</span>
                                                        <span class="text-gray-700 block mb-2" x-text="comparison.breed2.info?.bred_for || 'Not specified'"></span>
                                                        <span class="text-xs text-gray-500 italic">The specific task or job this breed was originally developed to perform. Understanding their history helps you know what activities they'll enjoy and what training challenges you might face. Dogs often still have these instincts today!</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <template x-if="comparison.breed2.info?.height">
                                                <div class="info-card-md">
                                                    <div class="flex items-start gap-2 mb-2">
                                                        <span class="text-lg">📏</span>
                                                        <div class="flex-1">
                                                            <div class="flex justify-between items-center mb-1">
                                                                <span class="font-bold text-gray-800">Height (Shoulder to Ground)</span>
                                                                <span class="text-gray-700 font-semibold" x-text="(comparison.breed2.info.height.metric || 'Not specified') + (comparison.breed2.info.height.metric ? ' cm' : '')"></span>
                                                            </div>
                                                            <span class="text-xs text-gray-500 italic">How tall the dog typically stands from paw to shoulder when fully grown. This helps you understand if they'll fit comfortably in your home and vehicle. Smaller dogs need less space, while larger dogs need more room to move.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                                
                                <!-- GIF Button -->
                                <div class="mt-6 pt-4 border-t border-gray-200">
                                    <button 
                                        class="compare-btn btn-bounce w-full px-4 py-3 rounded-lg font-semibold"
                                        @click="showGifModal('breed2')"
                                        x-show="comparison.breed2"
                                    >
                                        🎬 View Fun GIF
                                    </button>
                                </div>
                            </div>
                            
                            <span class="paw-icon absolute right-4 top-4">🐾</span>

                            <!-- Modal -->
                            <div 
                                x-show="gifModalVisible && gifModalBreed === 'breed2'" 
                                style="display: none;" 
                                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                                @click.self="gifModalVisible = false"
                            >
                                <div 
                                    class="bg-white rounded-lg p-6 shadow-lg max-w-lg w-full relative"
                                    @click.stop
                                >
                                    <button 
                                        class="image-popup-close absolute top-2 right-2"
                                        @click="gifModalVisible = false"
                                    >Close</button>
                                    <div class="flex flex-col items-center">
                                        <template x-if="gifUrl">
                                            <img :src="gifUrl" alt="Fun GIF" class="mb-4 rounded-lg max-h-80">
                                        </template>
                                        <template x-if="!gifUrl">
                                            <div class="text-gray-500">Loading GIF...</div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reset Button -->
                    <div class="text-center mt-12">
                        <button 
                            @click="comparison = null; breed1 = ''; breed2 = '';"
                            class="inline-flex items-center gap-2 px-8 py-3 border-2 border-gray-300 rounded-xl shadow-sm text-base font-semibold text-gray-700 bg-white hover:bg-gray-50 hover:border-[var(--color-primary)] focus:outline-none focus:ring-4 focus:ring-[color-mix(in_oklab,var(--color-primary)_20%,white)] transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
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
        gifModalVisible: false,
        gifModalBreed: '',
        gifUrl: '',

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
        },

        calculateCompatibility(breed1, breed2) {
            if (!breed1?.info || !breed2?.info) return 0;
            
            let scores = [];
            
            // 1. Temperament Similarity (Weight: 30%)
            if (breed1.info.temperament && breed2.info.temperament) {
                const temp1 = breed1.info.temperament.toLowerCase().split(/[,\s]+/).filter(t => t.length > 2);
                const temp2 = breed2.info.temperament.toLowerCase().split(/[,\s]+/).filter(t => t.length > 2);
                const commonTraits = temp1.filter(t => temp2.includes(t)).length;
                const totalTraits = new Set([...temp1, ...temp2]).size;
                if (totalTraits > 0) {
                    const similarity = (commonTraits / totalTraits) * 100;
                    scores.push({ value: similarity, weight: 0.30 });
                }
            }
            
            // 2. Weight/Size Similarity (Weight: 25%)
            if (breed1.info.weight?.metric && breed2.info.weight?.metric) {
                const getAvgWeight = (metric) => {
                    const nums = metric.match(/\d+/g);
                    if (!nums || nums.length === 0) return 0;
                    return nums.reduce((a, b) => parseFloat(a) + parseFloat(b), 0) / nums.length;
                };
                const w1 = getAvgWeight(breed1.info.weight.metric);
                const w2 = getAvgWeight(breed2.info.weight.metric);
                if (w1 > 0 && w2 > 0) {
                    const diff = Math.abs(w1 - w2);
                    const avgWeight = (w1 + w2) / 2;
                    const similarity = Math.max(0, (1 - diff / avgWeight)) * 100;
                    scores.push({ value: similarity, weight: 0.25 });
                }
            }
            
            // 3. Lifespan Similarity (Weight: 15%)
            if (breed1.info.life_span && breed2.info.life_span) {
                const getAvgLifespan = (span) => {
                    const nums = span.match(/\d+/g);
                    if (!nums || nums.length === 0) return 0;
                    return nums.reduce((a, b) => parseFloat(a) + parseFloat(b), 0) / nums.length;
                };
                const l1 = getAvgLifespan(breed1.info.life_span);
                const l2 = getAvgLifespan(breed2.info.life_span);
                if (l1 > 0 && l2 > 0) {
                    const diff = Math.abs(l1 - l2);
                    const maxDiff = 15; // Maximum expected difference in years
                    const similarity = Math.max(0, (1 - diff / maxDiff)) * 100;
                    scores.push({ value: similarity, weight: 0.15 });
                }
            }
            
            // 4. Cat-specific: Intelligence (Weight: 15%)
            if (this.animalType === 'cat' && breed1.info.intelligence && breed2.info.intelligence) {
                const diff = Math.abs(breed1.info.intelligence - breed2.info.intelligence);
                const similarity = ((5 - diff) / 5) * 100;
                scores.push({ value: similarity, weight: 0.15 });
            }
            
            // 5. Cat-specific: Energy Level (Weight: 15%)
            if (this.animalType === 'cat' && breed1.info.energy_level && breed2.info.energy_level) {
                const diff = Math.abs(breed1.info.energy_level - breed2.info.energy_level);
                const similarity = ((5 - diff) / 5) * 100;
                scores.push({ value: similarity, weight: 0.15 });
            }
            
            // 6. Dog-specific: Breed Group Match (Weight: 20%)
            if (this.animalType === 'dog' && breed1.info.breed_group && breed2.info.breed_group) {
                const similarity = breed1.info.breed_group === breed2.info.breed_group ? 100 : 25;
                scores.push({ value: similarity, weight: 0.20 });
            }
            
            // 7. Dog-specific: Bred For Similarity (Weight: 10%)
            if (this.animalType === 'dog' && breed1.info.bred_for && breed2.info.bred_for) {
                const purpose1 = breed1.info.bred_for.toLowerCase().split(/[,\s]+/).filter(p => p.length > 3);
                const purpose2 = breed2.info.bred_for.toLowerCase().split(/[,\s]+/).filter(p => p.length > 3);
                const commonPurposes = purpose1.filter(p => purpose2.includes(p)).length;
                const totalPurposes = new Set([...purpose1, ...purpose2]).size;
                if (totalPurposes > 0) {
                    const similarity = (commonPurposes / totalPurposes) * 100;
                    scores.push({ value: similarity, weight: 0.10 });
                }
            }
            
            // 8. Dog-specific: Height Similarity (Weight: 10%)
            if (this.animalType === 'dog' && breed1.info.height?.metric && breed2.info.height?.metric) {
                const getAvgHeight = (metric) => {
                    const nums = metric.match(/\d+/g);
                    if (!nums || nums.length === 0) return 0;
                    return nums.reduce((a, b) => parseFloat(a) + parseFloat(b), 0) / nums.length;
                };
                const h1 = getAvgHeight(breed1.info.height.metric);
                const h2 = getAvgHeight(breed2.info.height.metric);
                if (h1 > 0 && h2 > 0) {
                    const diff = Math.abs(h1 - h2);
                    const avgHeight = (h1 + h2) / 2;
                    const similarity = Math.max(0, (1 - diff / avgHeight)) * 100;
                    scores.push({ value: similarity, weight: 0.10 });
                }
            }
            
            // Calculate weighted average
            if (scores.length === 0) return 50; // Default if no data
            
            const totalWeight = scores.reduce((sum, s) => sum + s.weight, 0);
            const weightedSum = scores.reduce((sum, s) => sum + (s.value * s.weight), 0);
            
            return Math.round(weightedSum / totalWeight);
        },

        async showGifModal(breedKey) {
            this.gifModalVisible = true;
            this.gifModalBreed = breedKey;
            this.gifUrl = '';
            let breedName = this.comparison[breedKey]?.info?.name || '';
            if (!breedName) return;
            // Add animal type for more accurate GIFs
            let animalTypeLabel = this.animalType === 'cat' ? 'cat' : 'dog';
            let searchQuery = `${breedName} ${animalTypeLabel}`;
            try {
                let res = await fetch(`https://tenor.googleapis.com/v2/search?q=${encodeURIComponent(searchQuery)}&key=AIzaSyDt9RwM_CZx4p9wrp72V4hQ24MTvvAzNyU&limit=1`);
                let data = await res.json();
                this.gifUrl = data.results?.[0]?.media_formats?.gif?.url || '';
            } catch (e) {
                this.gifUrl = '';
            }
        }
    }
}
</script>
@endpush

@push('styles')
<style>
    :root {
        /* Material Design Color Palette */
        --md-primary: #6200EE;
        --md-primary-variant: #3700B3;
        --md-secondary: #03DAC6;
        --md-secondary-variant: #018786;
        --md-surface: #FFFFFF;
        --md-error: #B00020;
        --md-on-primary: #FFFFFF;
        --md-on-secondary: #000000;
        --md-on-surface: #000000;
        --md-on-error: #FFFFFF;

        /* Material Design Elevation Shadows */
        --md-elevation-0: none;
        --md-elevation-1: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
        --md-elevation-2: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        --md-elevation-3: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
        --md-elevation-4: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
        --md-elevation-6: 0 19px 38px rgba(0, 0, 0, 0.30), 0 15px 12px rgba(0, 0, 0, 0.22);
    }

    /* Paw background pattern for pet-friendly vibe */
    body.compare-bg {
        background: #F5F5F5;
    }
    
    .paw-icon {
        position: absolute;
        opacity: 0.08;
        font-size: 2.5rem;
        pointer-events: none;
        z-index: 0;
    }
    
    /* Animated card entrance */
    .animate-card {
        animation: cardIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    @keyframes cardIn {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    
    /* Button bounce on hover */
    .btn-bounce:hover {
        transform: translateY(-2px);
    }
    
    /* Material Design breed result card */
    .breed-result-card {
        background: var(--md-surface);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--md-elevation-2);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        position: relative;
        border-left: 4px solid transparent;
        display: flex;
        flex-direction: column;
    }
    
    .breed-result-card > div:first-child {
        flex-shrink: 0;
    }
    
    .breed-result-card > div:nth-child(2) {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .breed-result-card .space-y-3 {
        flex: 1;
    }
    
    .breed-result-card:hover {
        box-shadow: var(--md-elevation-6);
        transform: translateY(-8px);
        border-left-color: var(--md-primary);
    }
    
    .breed-image {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        object-fit: cover;
        width: 100%;
        height: 100%;
    }
    
    .breed-result-card:hover .breed-image {
        transform: scale(1.05);
    }
    
    /* Material Design compatibility badge */
    .compatibility-badge-md {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 64px;
        padding: 8px 16px;
        border-radius: 16px;
        font-size: 1.125rem;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: var(--md-elevation-2);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .compatibility-badge-md:hover {
        box-shadow: var(--md-elevation-4);
        transform: scale(1.05);
    }
    
    /* Material Design info card */
    .info-card-md {
        background: #F5F5F5;
        padding: 16px;
        border-radius: 12px;
        border-left: 4px solid var(--md-primary);
        margin-bottom: 12px;
        box-shadow: var(--md-elevation-1);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .info-card-md:hover {
        background: #EEEEEE;
        box-shadow: var(--md-elevation-2);
        transform: translateX(4px);
    }
    
    /* Animate compare button */
    .compare-btn {
        background: linear-gradient(90deg, var(--md-primary), var(--md-primary-variant));
        color: white;
        border: none;
        box-shadow: var(--md-elevation-2);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
    }
    
    .compare-btn:hover {
        box-shadow: var(--md-elevation-4);
        transform: translateY(-2px);
    }
    
    /* Animate recent cards */
    .recent-card {
        animation: cardIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 12px;
        background: var(--md-surface);
        box-shadow: var(--md-elevation-1);
        position: relative;
        overflow: hidden;
        border: 1px solid #E0E0E0;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .recent-card:hover {
        box-shadow: var(--md-elevation-3);
        transform: translateY(-4px);
    }
    
    .recent-card .paw-icon {
        left: 8px;
        bottom: 8px;
        font-size: 1.5rem;
        opacity: 0.08;
    }
    
    /* Popup image modal styles */
    .image-popup-close {
        background: var(--md-primary);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 18px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: var(--md-elevation-2);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .image-popup-close:hover {
        box-shadow: var(--md-elevation-4);
        transform: translateY(-2px);
    }
    
    /* Compatibility comparison section */
    .compatibility-comparison {
        background: linear-gradient(135deg, #F5F5F5 0%, #FAFAFA 100%);
        border-radius: 16px;
        padding: 32px;
        margin: 24px 0;
        box-shadow: var(--md-elevation-2);
        border-left: 4px solid var(--md-secondary);
    }
    
    .compatibility-bar {
        height: 12px;
        background: #E0E0E0;
        border-radius: 6px;
        overflow: hidden;
        position: relative;
    }
    
    .compatibility-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--md-primary), var(--md-secondary));
        border-radius: 6px;
        transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 0 8px rgba(98, 0, 238, 0.4);
    }
</style>
@endpush

@push('scripts')
<script>
document.body.classList.add('compare-bg');
</script>
@endpush