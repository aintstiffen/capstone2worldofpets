/**
 * Pet Facts API Integration
 * Fetches random dog and cat facts and displays them in a toast notification
 * Only shows once per day on first visit
 */

document.addEventListener('DOMContentLoaded', function() {
    // Check if we've shown a fact today already
    const today = new Date().toLocaleDateString();
    const lastShown = localStorage.getItem('petFactLastShown');
    
    if (lastShown !== today) {
        fetchRandomPetFact();
        // Mark that we've shown a fact today
        localStorage.setItem('petFactLastShown', today);
    }
});

/**
 * Fetch a random pet fact (either dog or cat)
 */
async function fetchRandomPetFact() {
    // Randomly choose between dog and cat facts
    const petType = Math.random() < 0.5 ? 'dog' : 'cat';
    
    if (petType === 'dog') {
        fetchRandomDogFact();
    } else {
        fetchRandomCatFact();
    }
}

/**
 * Fetch a random dog fact from the API
 */
async function fetchRandomDogFact() {
    try {
        // Show loading indicator or placeholder if needed
        const loadingToast = showToast('Loading interesting dog fact...', '🔄');
        
        const response = await fetch('https://dogapi.dog/api/v2/facts');
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await response.json();
        
        // Get the fact from the API structure
        const factText = data.data[0].attributes.body;
        
        // Remove loading toast if it exists
        if (loadingToast && document.body.contains(loadingToast)) {
            document.body.removeChild(loadingToast);
        }
        
        // Display the fact in a toast with dog styling
        showToast(factText, '🐶', 'Did You Know? (Dog Fact)', 10000, 'dog');
        
    } catch (error) {
        console.error('Error fetching dog fact:', error);
        
        // Try cat facts as a fallback
        fetchRandomCatFact();
    }
}

/**
 * Fetch a random cat fact from the API
 */
async function fetchRandomCatFact() {
    try {
        // Show loading indicator or placeholder if needed
        const loadingToast = showToast('Loading interesting cat fact...', '🔄');
        
        const response = await fetch('https://catfact.ninja/fact');
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await response.json();
        
        // Get the fact from the API structure
        const factText = data.fact;
        
        // Remove loading toast if it exists
        if (loadingToast && document.body.contains(loadingToast)) {
            document.body.removeChild(loadingToast);
        }
        
        // Display the fact in a toast with cat styling
        showToast(factText, '🐱', 'Did You Know? (Cat Fact)', 10000, 'cat');
        
    } catch (error) {
        console.error('Error fetching cat fact:', error);
        
        // Show error toast
        showToast('Could not fetch a pet fact at this time. Please try again later.', '❌');
    }
}

/**
 * Display a toast notification with the provided fact
 * @param {string} fact - The text to display in the toast
 * @param {string} [iconEmoji='🐶'] - The emoji to use as an icon
 * @param {string} [title='Did You Know?'] - The title to show in the header
 * @param {number} [duration=10000] - How long to display the toast in milliseconds
 * @param {string} [petType=''] - The type of pet fact (dog or cat) for styling
 * @returns {HTMLElement} - The toast DOM element
 */
function showToast(fact, iconEmoji = '🐶', title = 'Did You Know?', duration = 10000, petType = '') {
    // Create toast element
    const toast = document.createElement('div');
    toast.classList.add('dog-fact-toast');
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'polite');
    
    // Add pet type for styling if provided
    if (petType) {
        toast.setAttribute('data-pet-type', petType);
    }
    
    // Create header with title
    const header = document.createElement('div');
    header.classList.add('dog-fact-header');
    header.innerHTML = `<strong>${title}</strong>`;
    
    // Create content
    const icon = document.createElement('div');
    icon.classList.add('dog-fact-icon');
    icon.innerHTML = iconEmoji;
    
    const content = document.createElement('div');
    content.classList.add('dog-fact-content');
    
    // For the main content
    const contentText = document.createElement('p');
    contentText.textContent = fact;
    content.appendChild(header);
    content.appendChild(contentText);
    
    const closeBtn = document.createElement('button');
    closeBtn.classList.add('dog-fact-close');
    closeBtn.innerHTML = '&times;';
    closeBtn.setAttribute('aria-label', 'Close');
    closeBtn.addEventListener('click', function() {
        hideToast(toast);
    });
    
    // Assemble toast
    toast.appendChild(icon);
    toast.appendChild(content);
    toast.appendChild(closeBtn);
    
    // Add to document
    document.body.appendChild(toast);
    
    // Auto-hide after duration
    if (duration > 0) {
        setTimeout(function() {
            hideToast(toast);
        }, duration);
    }
    
    return toast;
}

/**
 * Hide the toast with a fade-out animation
 * @param {HTMLElement} toast - The toast element to hide
 */
function hideToast(toast) {
    if (document.body.contains(toast)) {
        toast.classList.add('fade-out');
        setTimeout(() => {
            if (document.body.contains(toast)) {
                document.body.removeChild(toast);
            }
        }, 500);
    }
}