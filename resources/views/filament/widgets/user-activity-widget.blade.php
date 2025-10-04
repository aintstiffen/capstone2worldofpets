{{-- Safe preview of stored image; works even if $record is undefined --}}
@php
    $path = null;

    if (isset($record) && !empty($record?->image)) {
        $path = $record->image;
    } elseif (isset($get) && is_callable($get)) {
        // Fallback: try current form state (avoid temporary livewire-tmp paths)
        $candidate = $get('image');
        if (is_string($candidate) && $candidate !== '' && !str_starts_with($candidate, 'livewire-tmp/')) {
            $path = $candidate;
        }
    }
@endphp

<div>
    @if($path)
        <div class="space-y-2">
            <div class="text-xs text-gray-600 dark:text-gray-400">Stored image</div>
            <div class="rounded border border-gray-200 dark:border-gray-600 bg-white/50 p-2 inline-block max-w-full">
                <img
                    src="{{ route('b2.image', ['path' => $path]) }}"
                    alt="Stored image"
                    class="max-h-48 rounded object-contain"
                    loading="lazy"
                >
            </div>
        </div>
    @else
        <div class="text-xs text-gray-500">No stored image yet.</div>
    @endif
</div>