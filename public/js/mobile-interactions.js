// Mobile-friendly interactions for the Fun Facts feature
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on a touch device
    const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
    
    if (isTouchDevice) {
        const hotspots = document.querySelectorAll('.group');
        let activeHotspot = null;
        
        // Add click functionality for tooltips on mobile
        hotspots.forEach(hotspot => {
            const tooltip = hotspot.querySelector('div[class*="hidden group-hover:block"]');
            
            hotspot.addEventListener('click', function(e) {
                e.stopPropagation();
                
                // Close any open tooltip first
                if (activeHotspot && activeHotspot !== hotspot) {
                    const activeTooltip = activeHotspot.querySelector('div[class*="hidden group-hover:block"]');
                    activeTooltip.classList.add('hidden');
                    activeTooltip.classList.remove('block');
                }
                
                // Toggle current tooltip
                if (tooltip.classList.contains('hidden')) {
                    tooltip.classList.remove('hidden');
                    tooltip.classList.add('block');
                    activeHotspot = hotspot;
                } else {
                    tooltip.classList.add('hidden');
                    tooltip.classList.remove('block');
                    activeHotspot = null;
                }
            });
        });
        
        // Close tooltip when clicking elsewhere on the page
        document.addEventListener('click', function() {
            if (activeHotspot) {
                const activeTooltip = activeHotspot.querySelector('div[class*="hidden group-hover:block"]');
                activeTooltip.classList.add('hidden');
                activeTooltip.classList.remove('block');
                activeHotspot = null;
            }
        });
    }
});