@php
    // Get image URL from the record
    $record = $getRecord();
    $imageUrl = $record?->image ?? $record?->image_url ?? null;
@endphp

<div class="space-y-4" x-data="{ showGrid: false, mouseX: 0, mouseY: 0 }">
    @if($imageUrl)
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Pet Image Preview
                </p>
                <button 
                    type="button"
                    @click="showGrid = !showGrid"
                    class="text-xs px-3 py-1.5 rounded bg-blue-500 hover:bg-blue-600 text-green-500 transition-colors font-medium"
                >
                    <span x-show="!showGrid">📐 Show Grid & Coordinates</span>
                    <span x-show="showGrid">✕ Hide Grid</span>
                </button>
            </div>
            
            <div class="relative inline-block max-w-full">
                <img 
                    src="{{ $imageUrl }}" 
                    alt="Pet Preview" 
                    class="max-w-full h-auto rounded-lg border-2 border-gray-300"
                    style="max-height: 500px;"
                    @mousemove="
                        if (showGrid) {
                            const rect = $event.currentTarget.getBoundingClientRect();
                            mouseX = Math.round(((($event.clientX - rect.left) / rect.width) * 100) * 10) / 10;
                            mouseY = Math.round(((($event.clientY - rect.top) / rect.height) * 100) * 10) / 10;
                        }
                    "
                    onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'bg-red-50 dark:bg-red-900/20 p-8 rounded-lg text-center border border-red-200 dark:border-red-800\'><svg class=\'mx-auto h-12 w-12 text-red-500\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z\'></path></svg><p class=\'mt-2 text-sm text-red-600 dark:text-red-400\'>Failed to load image</p><p class=\'text-xs text-gray-600 dark:text-gray-400 mt-2 break-all\'>{{ addslashes($imageUrl) }}</p></div>';"
                >
                
                <!-- Floating Tooltip Following Mouse -->
                <div 
                    x-show="showGrid"
                    x-transition
                    class="absolute pointer-events-none"
                    style="z-index: 20;"
                    :style="`left: ${mouseX}%; top: ${mouseY}%; transform: translate(15px, -35px);`"
                >
                    <div class="bg-gray-900 dark:bg-gray-100 text-green-500
                     dark:text-gray-900 px-3 py-2 rounded-lg shadow-lg border-2 border-blue-500 whitespace-nowrap">
                        <div class="flex items-center gap-3 text-xs font-bold">
                            <div class="flex items-center gap-1">
                                <span class="text-blue-400 dark:text-blue-600">X:</span>
                                <span class="font-mono" x-text="mouseX + '%'"></span>
                            </div>
                            <div class="w-px h-4 bg-gray-600 dark:bg-gray-400"></div>
                            <div class="flex items-center gap-1">
                                <span class="text-indigo-400 dark:text-indigo-600">Y:</span>
                                <span class="font-mono" x-text="mouseY + '%'"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Grid Overlay -->
                <div 
                    x-show="showGrid"
                    x-transition
                    class="absolute inset-0 pointer-events-none"
                    style="z-index: 10;"
                >
                    <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 100 100">
                        <!-- Vertical lines every 10% -->
                        <line x1="10" y1="0" x2="10" y2="100" stroke="rgba(59, 130, 246, 0.5)" stroke-width="0.2" stroke-dasharray="1,1"/>
                        <line x1="20" y1="0" x2="20" y2="100" stroke="rgba(59, 130, 246, 0.5)" stroke-width="0.2" stroke-dasharray="1,1"/>
                        <line x1="30" y1="0" x2="30" y2="100" stroke="rgba(59, 130, 246, 0.5)" stroke-width="0.2" stroke-dasharray="1,1"/>
                        <line x1="40" y1="0" x2="40" y2="100" stroke="rgba(59, 130, 246, 0.5)" stroke-width="0.2" stroke-dasharray="1,1"/>
                        <line x1="50" y1="0" x2="50" y2="100" stroke="rgba(220, 38, 38, 0.6)" stroke-width="0.4"/>
                        <line x1="60" y1="0" x2="60" y2="100" stroke="rgba(59, 130, 246, 0.5)" stroke-width="0.2" stroke-dasharray="1,1"/>
                        <line x1="70" y1="0" x2="70" y2="100" stroke="rgba(59, 130, 246, 0.5)" stroke-width="0.2" stroke-dasharray="1,1"/>
                        <line x1="80" y1="0" x2="80" y2="100" stroke="rgba(59, 130, 246, 0.5)" stroke-width="0.2" stroke-dasharray="1,1"/>
                        <line x1="90" y1="0" x2="90" y2="100" stroke="rgba(59, 130, 246, 0.5)" stroke-width="0.2" stroke-dasharray="1,1"/>
                        
                        <!-- Horizontal lines every 10% -->
                        <line x1="0" y1="10" x2="100" y2="10" stroke="rgba(59, 130, 246, 0.5)" stroke-width="0.2" stroke-dasharray="1,1"/>
                        <line x1="0" y1="20" x2="100" y2="20" stroke="rgba(59, 130, 246, 0.5)" stroke-width="0.2" stroke-dasharray="1,1"/>
                        <line x1="0" y1="30" x2="100" y2="30" stroke="rgba(59, 130, 246, 0.5)" stroke-width="0.2" stroke-dasharray="1,1"/>
                        <line x1="0" y1="40" x2="100" y2="40" stroke="rgba(59, 130, 246, 0.5)" stroke-width="0.2" stroke-dasharray="1,1"/>
                        <line x1="0" y1="50" x2="100" y2="50" stroke="rgba(220, 38, 38, 0.6)" stroke-width="0.4"/>
                        <line x1="0" y1="60" x2="100" y2="60" stroke="rgba(59, 130, 246, 0.5)" stroke-width="0.2" stroke-dasharray="1,1"/>
                        <line x1="0" y1="70" x2="100" y2="70" stroke="rgba(59, 130, 246, 0.5)" stroke-width="0.2" stroke-dasharray="1,1"/>
                        <line x1="0" y1="80" x2="100" y2="80" stroke="rgba(59, 130, 246, 0.5)" stroke-width="0.2" stroke-dasharray="1,1"/>
                        <line x1="0" y1="90" x2="100" y2="90" stroke="rgba(59, 130, 246, 0.5)" stroke-width="0.2" stroke-dasharray="1,1"/>
                        
                        <!-- Coordinate labels -->
                        <text x="2" y="4" font-size="4" fill="rgba(59, 130, 246, 0.9)" font-weight="bold">0%</text>
                        <text x="47" y="4" font-size="4" fill="rgba(220, 38, 38, 0.9)" font-weight="bold">50%</text>
                        <text x="93" y="4" font-size="4" fill="rgba(59, 130, 246, 0.9)" font-weight="bold">100%</text>
                        
                        <text x="2" y="6" font-size="4" fill="rgba(59, 130, 246, 0.9)" font-weight="bold">0%</text>
                        <text x="2" y="52" font-size="4" fill="rgba(220, 38, 38, 0.9)" font-weight="bold">50%</text>
                        <text x="2" y="98" font-size="4" fill="rgba(59, 130, 246, 0.9)" font-weight="bold">100%</text>
                    </svg>
                </div>
            </div>
            
            <!-- Real-Time Coordinate Display -->
            <div 
                x-show="showGrid"
                x-transition
                class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-lg"
            >
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    <p class="text-sm font-bold text-blue-900 dark:text-blue-100">
                        Real-Time Mouse Position
                    </p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white dark:bg-gray-700 p-3 rounded-lg border-l-4 border-blue-500">
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">X Position (Horizontal)</div>
                        <div class="flex items-baseline gap-1">
                            <span class="text-2xl font-bold font-mono text-blue-600 dark:text-blue-400" x-text="mouseX"></span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">%</span>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-700 p-3 rounded-lg border-l-4 border-indigo-500">
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Y Position (Vertical)</div>
                        <div class="flex items-baseline gap-1">
                            <span class="text-2xl font-bold font-mono text-indigo-600 dark:text-indigo-400" x-text="mouseY"></span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">%</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3 p-3 bg-green-1000 dark:bg-blue-900/30 rounded-lg">
                    <div class="flex items-start gap-2">
                        <span class="text-lg">💡</span>
                        <div class="text-xs text-blue-900 dark:text-blue-100">
                            <strong>How to use:</strong> Hover your mouse over different parts of the image. 
                            The coordinates update in real-time. Use these X and Y values when adding hotspots 
                            (e.g., <code class="bg-blue-200 dark:bg-blue-800 px-1 rounded">position_x: <span x-text="mouseX"></span></code> 
                            and <code class="bg-blue-200 dark:bg-blue-800 px-1 rounded">position_y: <span x-text="mouseY"></span></code>).
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                No image URL available. Please add an image URL to see the preview.
            </p>
        </div>
    @endif
</div>

