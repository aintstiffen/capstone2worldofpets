// Scroll-triggered fade-in animation for elements with .animated-fade-in
// Uses Intersection Observer for performance and broad browser support

document.addEventListener('DOMContentLoaded', function () {
  const animatedEls = document.querySelectorAll('.animated-fade-in');
  const observer = new window.IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      } else {
        entry.target.classList.remove('visible');
      }
    });
  }, {
    threshold: 0.1
  });

  animatedEls.forEach(el => observer.observe(el));
});

// Optionally, you can add fade-out logic for .animated-fade-out if needed
