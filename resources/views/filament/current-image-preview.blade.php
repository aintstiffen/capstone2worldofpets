@php($path = $record?->image)
@if($path)
    <div class="space-y-2">
        <div class="text-xs text-gray-600 dark:text-gray-400">Stored image (from B2)</div>
        <div class="rounded border border-gray-200 dark:border-gray-600 bg-white/50 p-2 inline-block max-w-full">
            <img src="{{ route('b2.image', ['path' => $path]) }}" alt="Current image" class="max-h-48 rounded object-contain" loading="lazy">
        </div>
    </div>
@else
    <div class="text-xs text-gray-500">No stored image yet.</div>
@endif
