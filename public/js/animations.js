// Scroll-triggered fade-in animation for elements with .animated-fade-in
// Uses Intersection Observer for performance and broad browser support

document.addEventListener('DOMContentLoaded', function () {
  const animatedEls = document.querySelectorAll('.animated-fade-in');
  // account for sticky header (~64px) so elements near the top become visible
  const observer = new window.IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      } else {
        entry.target.classList.remove('visible');
      }
    });
  }, {
    // threshold low so partial visibility triggers; rootMargin top negative to
    // treat elements below the sticky header as visible earlier on mobile.
    threshold: 0.05,
    rootMargin: '-80px 0px 0px 0px'
  });

  animatedEls.forEach(el => observer.observe(el));
});

// Optionally, you can add fade-out logic for .animated-fade-out if needed
