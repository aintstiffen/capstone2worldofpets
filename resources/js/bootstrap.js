import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Import Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;

// Initialize Alpine
Alpine.start();
