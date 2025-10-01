// Enhanced navigation scroll functionality
document.addEventListener('DOMContentLoaded', function() {
    // This will ensure the header behaves correctly when scrolling
    let lastScrollY = 0;
    let ticking = false;
    
    function handleScroll() {
        // If we're on mobile, close the menu when scrolling down
        if (window.innerWidth < 768) {
            const header = document.querySelector('header');
            
            if (window.scrollY > lastScrollY && window.scrollY > 100) {
                // Scrolling down - hide mobile menu if open
                if (header && typeof Alpine !== 'undefined') {
                    const headerData = Alpine.$data(header);
                    if (headerData && headerData.mobileMenuOpen) {
                        headerData.mobileMenuOpen = false;
                    }
                }
            }
            
            lastScrollY = window.scrollY;
        }
        
        ticking = false;
    }
    
    // Add event listener for scroll
    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(function() {
                handleScroll();
                ticking = false;
            });
            
            ticking = true;
        }
    }, { passive: true });
});