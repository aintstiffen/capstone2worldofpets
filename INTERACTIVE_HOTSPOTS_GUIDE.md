# Interactive Image Hotspots & Fun Facts Guide

## Overview
This feature allows you to add interactive hotspots to pet breed images in the Filament admin panel. Users can then hover over these hotspots on the public-facing pages to see fun facts about specific features.

## How to Use in Filament Admin

### 1. Adding/Editing a Pet Breed

1. Navigate to the Pets resource in Filament
2. Create a new pet or edit an existing one
3. Fill in the basic information (name, category, etc.)
4. Select a breed from the API dropdown - this will automatically populate the image URL

### 2. Adding Hotspots via Image Preview

Once you have an image URL:

1. Expand the **"Image Preview"** section
2. You'll see your pet image displayed
3. **Click directly on the image** where you want to add a hotspot (e.g., on the ears, eyes, nose, etc.)
4. A prompt will appear asking for the feature name
5. Enter one of these feature names:
   - `ears`
   - `eyes`
   - `nose`
   - `tail`
   - `paws`
   - `coat`
   - `whiskers`
   - `mouth`
6. After entering the feature name, another prompt will ask for a fun fact
7. Enter an interesting fact about that feature
8. The hotspot will appear on the image with a blue circle
9. Repeat for other features

### 3. Managing Hotspots Manually

If you prefer manual control, expand the **"Interactive Hotspots & Fun Facts"** section:

#### Hotspots Repeater
- **Feature**: Select the body part (ears, eyes, etc.)
- **Position X**: Horizontal position as percentage (0-100%)
- **Position Y**: Vertical position as percentage (0-100%)
- **Width**: Size of the hotspot area in pixels
- **Height**: Size of the hotspot area in pixels

#### Fun Facts Repeater
- **Feature**: Must match a hotspot feature name
- **Fun Fact**: The text that appears when users hover over the hotspot

### 4. Helper Features

- **Show Helper**: Click to display a grid overlay on the image to help position hotspots more accurately
- **Current Hotspots List**: View all added hotspots with their positions
- **Remove Hotspot**: Click the "Remove" button or the X on a hotspot tooltip to delete it

## Frontend Display

### How Users See It

On the public breed detail pages (`/dogs/{slug}` or `/cats/{slug}`):

1. The pet image is displayed with invisible hotspot areas
2. When users **hover over the image**, all hotspot areas become slightly visible with a pulsing animation
3. When users **hover over a specific hotspot**, a tooltip appears with the fun fact
4. The tooltip is intelligently positioned based on the hotspot location:
   - Left side hotspots → tooltip appears on the right
   - Right side hotspots → tooltip appears on the left
   - Top hotspots → tooltip appears below
   - Other hotspots → tooltip appears above

### Color Coding

Each feature type has a distinct color:
- **Ears**: Blue
- **Eyes**: Green
- **Tail**: Amber
- **Paws**: Purple
- **Nose**: Pink
- **Coat**: Orange
- **Whiskers**: Gray (default)
- **Mouth**: Gray (default)

## Technical Details

### Database Structure

The `pets` table has two JSON columns:

```php
'hotspots' => [
    [
        'feature' => 'ears',
        'position_x' => 50,  // percentage
        'position_y' => 15,  // percentage
        'width' => 40,       // pixels
        'height' => 40       // pixels
    ],
    // ... more hotspots
]

'fun_facts' => [
    [
        'feature' => 'ears',
        'fact' => 'Their floppy ears help protect the ear canal from water and debris.'
    ],
    // ... more facts
]
```

### Files Modified/Created

1. **app/Filament/Resources/PetResource.php**
   - Added image preview section
   - Added hotspots and fun_facts repeaters

2. **resources/views/filament/components/image-preview.blade.php**
   - Custom Filament component for interactive image editing
   - Uses Alpine.js for interactivity
   - Allows clicking on image to add hotspots

3. **app/Models/Pet.php**
   - Added `hotspots` and `fun_facts` to fillable array
   - Already had casts for JSON fields

4. **resources/views/dogs/show.blade.php** (already existed)
   - Displays hotspots with tooltips on hover
   - Handles default facts if none are set

5. **resources/views/cats/show.blade.php** (already existed)
   - Same functionality as dogs/show.blade.php

## Best Practices

### Positioning Tips

1. **Use the helper grid**: Enable the helper to see percentage guidelines
2. **Standard positions**:
   - Ears: ~15-20% from top, 40-60% horizontal
   - Eyes: ~25-35% from top, 40-60% horizontal
   - Nose: ~40-50% from top, 45-55% horizontal
   - Tail: ~60-80% from top, 75-90% horizontal (dogs) or 10-25% (cats)
   - Paws: ~80-90% from top, 20-40% horizontal

### Writing Good Fun Facts

1. Keep facts concise (under 200 characters is ideal)
2. Make them breed-specific when possible
3. Include interesting or surprising information
4. Use simple, accessible language
5. Example good facts:
   - "Their floppy ears help protect the ear canal from water and debris."
   - "Can rotate their ears independently to precisely locate sounds."
   - "Have over 300 million scent receptors compared to 5-6 million in humans."

### Hotspot Sizes

- **Small features** (nose, eyes): 30-40px
- **Medium features** (ears, paws): 40-60px
- **Large features** (coat, body): 60-100px

## Troubleshooting

### Hotspots not appearing
- Ensure the image URL is valid
- Check that position percentages are between 0-100
- Verify the feature name matches between hotspots and fun_facts

### Tooltips in wrong position
- Adjust the position_x and position_y values
- Use the helper grid for more accurate positioning

### Changes not saving
- Make sure to save the form after adding hotspots
- Check browser console for any JavaScript errors

## Examples

### Golden Retriever Example

```json
{
  "hotspots": [
    {"feature": "ears", "position_x": 50, "position_y": 15, "width": 50, "height": 45},
    {"feature": "eyes", "position_x": 50, "position_y": 30, "width": 50, "height": 30},
    {"feature": "nose", "position_x": 50, "position_y": 45, "width": 30, "height": 30},
    {"feature": "tail", "position_x": 85, "position_y": 70, "width": 40, "height": 50}
  ],
  "fun_facts": [
    {"feature": "ears", "fact": "Their floppy ears help protect the ear canal from water and debris."},
    {"feature": "eyes", "fact": "Golden Retrievers have a soft, intelligent expression that's part of their breed standard."},
    {"feature": "nose", "fact": "Their excellent sense of smell makes them perfect for search and rescue work."},
    {"feature": "tail", "fact": "The tail acts as a rudder when swimming and shows their happy, friendly nature."}
  ]
}
```

### Persian Cat Example

```json
{
  "hotspots": [
    {"feature": "ears", "position_x": 50, "position_y": 10, "width": 45, "height": 35},
    {"feature": "eyes", "position_x": 50, "position_y": 35, "width": 50, "height": 30},
    {"feature": "nose", "position_x": 50, "position_y": 45, "width": 25, "height": 25},
    {"feature": "coat", "position_x": 50, "position_y": 65, "width": 80, "height": 60}
  ],
  "fun_facts": [
    {"feature": "ears", "fact": "Their small ears are set far apart and tilted slightly forward, nestled in luxurious fur."},
    {"feature": "eyes", "fact": "Persian cats have large, round eyes that give them a sweet, doll-like expression."},
    {"feature": "nose", "fact": "Their distinctive flat face and 'squished' nose is the result of selective breeding."},
    {"feature": "coat", "fact": "Persian cats have one of the longest and densest coats of all cat breeds, requiring daily grooming."}
  ]
}
```

## API Integration Note

The image URLs are fetched from:
- **Dogs**: TheDogAPI (https://thedogapi.com/)
- **Cats**: TheCatAPI (https://thecatapi.com/)

These APIs provide high-quality breed images that work well with the hotspot feature.
