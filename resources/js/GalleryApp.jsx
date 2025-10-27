import React from 'react';
import { createRoot } from 'react-dom/client';
import CircularGallery from "./components/CircularGallery.jsx";

const galleryContainer = document.getElementById('pet-gallery-root');

if (galleryContainer) {
  const items = JSON.parse(galleryContainer.dataset.items || '[]');

  createRoot(galleryContainer).render(
    <div style={{ height: '400px', position: 'relative' }}>
      <CircularGallery
        items={items}      // Your Blade-processed images
        bend={2}           // as in original usage
        textColor="#ffffff"
        borderRadius={0.05}
        scrollEase={0.02}
      />
    </div>
  );
}
