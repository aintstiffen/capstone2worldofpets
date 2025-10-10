    {{-- Footer --}}
    <footer class="border-t bg-[var(--color-muted)]">
        <div class="container mx-auto px-4 md:px-6">
            <div class="py-10 grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 200" class="w-16 h-16">
  <!-- Dog -->
  <g>
    <!-- Head -->
    <circle cx="80" cy="70" r="35" fill="#F4D19B" />
    <!-- Ears -->
    <ellipse cx="45" cy="60" rx="10" ry="20" fill="#C69763" />
    <ellipse cx="115" cy="60" rx="10" ry="20" fill="#C69763" />
    <!-- Eyes -->
    <circle cx="68" cy="68" r="5" fill="#333" />
    <circle cx="92" cy="68" r="5" fill="#333" />
    <!-- Nose -->
    <circle cx="80" cy="80" r="3" fill="#000" />
    <!-- Mouth -->
    <path d="M70 90 Q80 100 90 90" stroke="#333" stroke-width="2" fill="none" />
    <!-- Body -->
    <ellipse cx="80" cy="140" rx="35" ry="40" fill="#F4D19B" />
    <!-- Paws -->
    <circle cx="60" cy="170" r="6" fill="#C69763" />
    <circle cx="100" cy="170" r="6" fill="#C69763" />
  </g>

  <!-- Cat -->
  <g>
    <!-- Head -->
    <circle cx="230" cy="70" r="30" fill="#F7B6C2" />
    <!-- Ears -->
    <polygon points="205,55 210,35 220,60" fill="#F7B6C2" />
    <polygon points="255,55 250,35 240,60" fill="#F7B6C2" />
    <!-- Eyes -->
    <circle cx="220" cy="65" r="4" fill="#333" />
    <circle cx="240" cy="65" r="4" fill="#333" />
    <!-- Nose -->
    <circle cx="230" cy="75" r="2.5" fill="#000" />
    <!-- Mouth -->
    <path d="M224 83 Q230 90 236 83" stroke="#333" stroke-width="2" fill="none" />
    <!-- Whiskers -->
    <path d="M200 75 H215 M245 75 H260" stroke="#333" stroke-width="1" />
    <!-- Body -->
    <ellipse cx="230" cy="140" rx="30" ry="35" fill="#F7B6C2" />
    <!-- Paws -->
    <circle cx="212" cy="165" r="5" fill="#F49FB0" />
    <circle cx="248" cy="165" r="5" fill="#F49FB0" />
    <!-- Tail -->
    <path d="M260 140 Q280 130 270 160" stroke="#F7B6C2" stroke-width="6" fill="none" />
  </g>
</svg>

                        <h3 class="text-lg font-bold">World of Pets</h3>
                    </div>
                    <p class="text-sm text-[var(--color-muted-foreground)]">
                        Educational platform for dog and cat breeds in the Philippines.
                    </p>
                </div>
                
                <div class="space-y-4">
                    <h3 class="text-sm font-medium">Explore</h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="/dogs" class="text-[var(--color-muted-foreground)] transition-colors hover:text-[var(--color-foreground)]">Dog Breeds</a></li>
                        <li><a href="/cats" class="text-[var(--color-muted-foreground)] transition-colors hover:text-[var(--color-foreground)]">Cat Breeds</a></li>
                    </ul>
                </div>
                
                <div class="space-y-4">
                    <h3 class="text-sm font-medium">Tools</h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="/assessment" class="text-[var(--color-muted-foreground)] transition-colors hover:text-[var(--color-foreground)]">Personality Assessment</a></li>
                        <li><a href="/compare" class="text-[var(--color-muted-foreground)] transition-colors hover:text-[var(--color-foreground)]">Breed Comparison</a></li>
                    </ul>
                </div>
                
                <div class="space-y-4">
                    <h3 class="text-sm font-medium">Legal</h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('privacy') }}" class="text-[var(--color-muted-foreground)] transition-colors hover:text-[var(--color-foreground)]">Privacy Policy</a></li>
                        <li><a href="{{ route('terms') }}" class="text-[var(--color-muted-foreground)] transition-colors hover:text-[var(--color-foreground)]">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="border-t py-6">
            <div class="container mx-auto px-4 md:px-6">
                <p class="text-sm text-[var(--color-muted-foreground)]">
                    © 2025 Pets of World. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
