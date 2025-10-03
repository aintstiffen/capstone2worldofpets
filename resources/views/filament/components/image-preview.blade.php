@php
    $imageUrl = $getState() ?? $getRecord()?->image ?? $getRecord()?->image_url;
    $hotspots = $getRecord()?->hotspots ?? [];
    $funFacts = $getRecord()?->fun_facts ?? [];
@endphp

<div x-data="{
    imageUrl: @js($imageUrl),
    hotspots: @js($hotspots),
    funFacts: @js($funFacts),
    showTooltip: null,
    showHelper: false,
    
    addHotspot(event) {
        const rect = event.currentTarget.getBoundingClientRect();
        const x = ((event.clientX - rect.left) / rect.width) * 100;
        const y = ((event.clientY - rect.top) / rect.height) * 100;
        
        // Get the feature from a prompt or default to 'custom'
        const feature = prompt('Enter feature name (ears, eyes, nose, tail, paws, coat, whiskers, mouth):');
        
        if (feature) {
            // Add to hotspots array
            const newHotspot = {
                feature: feature.toLowerCase(),
                position_x: Math.round(x * 10) / 10,
                position_y: Math.round(y * 10) / 10,
                width: 40,
                height: 40
            };
            
            this.hotspots.push(newHotspot);
            
            // Update the form field
            this.updateFormField();
            
            // Prompt for fun fact
            const fact = prompt('Enter a fun fact about the ' + feature + ':');
            if (fact) {
                this.funFacts.push({
                    feature: feature.toLowerCase(),
                    fact: fact
                });
                this.updateFunFactsField();
            }
        }
    },
    
    updateFormField() {
        // Update the hidden hotspots field
        const hotspotsInput = document.querySelector('[wire\\\\:model=\"data.hotspots\"]');
        if (hotspotsInput) {
            hotspotsInput.value = JSON.stringify(this.hotspots);
            hotspotsInput.dispatchEvent(new Event('input'));
        }
        
        // Dispatch Livewire event to update the form
        if (window.Livewire) {
            @this.set('data.hotspots', this.hotspots);
        }
    },
    
    updateFunFactsField() {
        // Update the hidden fun_facts field
        const funFactsInput = document.querySelector('[wire\\\\:model=\"data.fun_facts\"]');
        if (funFactsInput) {
            funFactsInput.value = JSON.stringify(this.funFacts);
            funFactsInput.dispatchEvent(new Event('input'));
        }
        
        // Dispatch Livewire event to update the form
        if (window.Livewire) {
            @this.set('data.fun_facts', this.funFacts);
        }
    },
    
    removeHotspot(index) {
        const feature = this.hotspots[index].feature;
        this.hotspots.splice(index, 1);
        this.updateFormField();
        
        // Also remove associated fun fact
        const factIndex = this.funFacts.findIndex(f => f.feature === feature);
        if (factIndex !== -1) {
            this.funFacts.splice(factIndex, 1);
            this.updateFunFactsField();
        }
    }
}" 
class="space-y-4">
    @if($imageUrl)
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Click on the image to add interactive hotspots
                </p>
                <button 
                    type="button"
                    @click="showHelper = !showHelper"
                    class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                    <span x-show="!showHelper">Show Helper</span>
                    <span x-show="showHelper">Hide Helper</span>
                </button>
            </div>
            
            <div class="relative inline-block max-w-full">
                <img 
                    :src="imageUrl" 
                    alt="Pet Preview" 
                    class="max-w-full h-auto rounded-lg cursor-crosshair border-2 border-dashed border-gray-300 hover:border-blue-500 transition-colors"
                    @click="addHotspot($event)"
                    style="max-height: 500px;"
                >
                
                <!-- Show existing hotspots -->
                <div class="absolute inset-0">
                    <template x-for="(hotspot, index) in hotspots" :key="index">
                        <div 
                            class="absolute cursor-pointer group"
                            :style="`top: ${hotspot.position_y}%; left: ${hotspot.position_x}%; transform: translate(-50%, -50%);`"
                            @mouseenter="showTooltip = index"
                            @mouseleave="showTooltip = null"
                        >
                            <!-- Hotspot circle -->
                            <div 
                                :style="`width: ${hotspot.width}px; height: ${hotspot.height}px;`"
                                class="rounded-full border-2 border-blue-500 bg-blue-500/20 group-hover:bg-blue-500/40 transition-all"
                            ></div>
                            
                            <!-- Tooltip -->
                            <div 
                                x-show="showTooltip === index"
                                x-transition
                                class="absolute z-10 p-2 bg-white dark:bg-gray-700 rounded-lg shadow-lg w-48 text-sm"
                                :style="`${hotspot.position_x < 30 ? 'left: 100%;' : hotspot.position_x > 70 ? 'right: 100%;' : 'bottom: 100%;'} ${hotspot.position_x < 30 || hotspot.position_x > 70 ? 'top: 0;' : 'left: 50%; transform: translateX(-50%);'}`"
                            >
                                <div class="flex justify-between items-start gap-2">
                                    <div>
                                        <strong class="capitalize text-gray-900 dark:text-gray-100" x-text="hotspot.feature"></strong>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                            Position: <span x-text="Math.round(hotspot.position_x)"></span>%, <span x-text="Math.round(hotspot.position_y)"></span>%
                                        </p>
                                    </div>
                                    <button 
                                        type="button"
                                        @click="removeHotspot(index)"
                                        class="text-red-500 hover:text-red-700 text-xs"
                                        title="Remove hotspot"
                                    >
                                        ✕
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                
                <!-- Helper overlay -->
                <div 
                    x-show="showHelper"
                    x-transition
                    class="absolute inset-0 pointer-events-none"
                >
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <!-- Grid lines -->
                        <line x1="25" y1="0" x2="25" y2="100" stroke="rgba(59, 130, 246, 0.3)" stroke-width="0.2"/>
                        <line x1="50" y1="0" x2="50" y2="100" stroke="rgba(59, 130, 246, 0.3)" stroke-width="0.2"/>
                        <line x1="75" y1="0" x2="75" y2="100" stroke="rgba(59, 130, 246, 0.3)" stroke-width="0.2"/>
                        <line x1="0" y1="25" x2="100" y2="25" stroke="rgba(59, 130, 246, 0.3)" stroke-width="0.2"/>
                        <line x1="0" y1="50" x2="100" y2="50" stroke="rgba(59, 130, 246, 0.3)" stroke-width="0.2"/>
                        <line x1="0" y1="75" x2="100" y2="75" stroke="rgba(59, 130, 246, 0.3)" stroke-width="0.2"/>
                    </svg>
                </div>
            </div>
            
            <!-- Current hotspots list -->
            <div x-show="hotspots.length > 0" class="mt-4">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Current Hotspots:</h4>
                <ul class="space-y-1">
                    <template x-for="(hotspot, index) in hotspots" :key="index">
                        <li class="flex items-center justify-between text-xs bg-white dark:bg-gray-700 p-2 rounded">
                            <span class="capitalize font-medium" x-text="hotspot.feature"></span>
                            <span class="text-gray-600 dark:text-gray-400">
                                Position: (<span x-text="Math.round(hotspot.position_x)"></span>%, <span x-text="Math.round(hotspot.position_y)"></span>%)
                            </span>
                            <button 
                                type="button"
                                @click="removeHotspot(index)"
                                class="text-red-500 hover:text-red-700"
                            >
                                Remove
                            </button>
                        </li>
                    </template>
                </ul>
            </div>
            
            <div x-show="hotspots.length === 0" class="mt-4 text-sm text-gray-500 dark:text-gray-400 italic">
                No hotspots added yet. Click on the image to add one.
            </div>
        </div>
    @else
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                Please add an image URL first to preview and add hotspots.
            </p>
        </div>
    @endif
</div>
