@php
    // Get image URL from the record
    $record = $getRecord();
    $imageUrl = $record?->image ?? $record?->image_url ?? null;
@endphp

<div class="space-y-4">
    @if($imageUrl)
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                Pet Image Preview
            </p>
            
            <div class="relative inline-block max-w-full">
                <img 
                    src="{{ $imageUrl }}" 
                    alt="Pet Preview" 
                    class="max-w-full h-auto rounded-lg border-2 border-gray-300"
                    style="max-height: 500px;"
                    onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'bg-red-50 dark:bg-red-900/20 p-8 rounded-lg text-center border border-red-200 dark:border-red-800\'><svg class=\'mx-auto h-12 w-12 text-red-500\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z\'></path></svg><p class=\'mt-2 text-sm text-red-600 dark:text-red-400\'>Failed to load image</p><p class=\'text-xs text-gray-600 dark:text-gray-400 mt-2 break-all\'>{{ addslashes($imageUrl) }}</p></div>';"
                >
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

