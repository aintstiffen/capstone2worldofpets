<?php

namespace App\Filament\Components;

use Filament\Forms\Components\FileUpload as BaseFileUpload;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUpload extends BaseFileUpload
{
    protected function setUp(): void
    {
        parent::setUp();

        // DB -> component (force array state for foreach)
        $this->formatStateUsing(function ($state) {
            $state = $this->normalizeToPath($state);
            return $this->coerceStateForComponent($state);
        });

        // Keep runtime state as array
        $this->afterStateHydrated(function (self $component, $state) {
            $component->state(
                $component->coerceStateForComponent(
                    $component->normalizeToPath($state)
                )
            );
        });

        // component -> DB (store scalar for single)
        $this->dehydrateStateUsing(function ($state) {
            $state = $this->normalizeToPath($state);

            if (! $this->isMultiple()) {
                if (is_array($state)) {
                    return $state[0] ?? null;
                }
                return $state ?: null;
            }

            return is_array($state) ? $state : array_filter([$state]);
        });

        // Override validation rules to skip image validation
        $this->rules(['sometimes']);
        
        // Preview metadata / URL (don't fail on exists/size)
        $this->getUploadedFileUsing(function (string $file, $storedFileNames): ?array {
            if (str_starts_with($file, 'http://') || str_starts_with($file, 'https://')) {
                $basename = basename(parse_url($file, PHP_URL_PATH) ?? 'file');
                $ext      = pathinfo($basename, PATHINFO_EXTENSION);
                return [
                    'name' => $basename,
                    'size' => null,
                    'type' => $this->guessMime($ext),
                    'url'  => $file,
                ];
            }

            $disk    = $this->getDiskName();
            $storage = \Illuminate\Support\Facades\Storage::disk($disk);

            $exists = false;
            try {
                $exists = $storage->exists($file);
            } catch (\Throwable $e) {
                \Log::warning('exists() failed (ignored) for preview', ['disk' => $disk, 'path' => $file, 'err' => $e->getMessage()]);
            }

            if ($disk === 's3') {
                $s3Url = config('filesystems.disks.s3.url') ?: rtrim(env('AWS_URL', ''), '');
                $url   = $s3Url
                    ? rtrim($s3Url, '/') . '/' . ltrim($file, '/')
                    : "https://" . config('filesystems.disks.s3.bucket') . ".s3." . config('filesystems.disks.s3.region') . ".amazonaws.com/" . ltrim($file, '/');
            } else {
                $url = '/storage/' . ltrim($file, '/');
            }

            $fileName = ($this->isMultiple() ? ($storedFileNames[$file] ?? null) : $storedFileNames) ?? basename($file);
            $ext      = pathinfo($file, PATHINFO_EXTENSION);

            $size = null;
            if ($exists) {
                try { $size = $storage->size($file); } catch (\Throwable $e) { /* ignore */ }
            }

            return [
                'name' => $fileName,
                'size' => $size,
                'type' => $this->guessMime($ext),
                'url'  => $url,
            ];
        });
    }

    // Convert full URL -> relative key
    protected function normalizeToPath($state)
    {
        if (empty($state)) return $state;

        if (is_array($state)) {
            return array_map(fn ($v) => $this->normalizeToPath($v), $state);
        }

        if (is_string($state) && (str_starts_with($state, 'http://') || str_starts_with($state, 'https://'))) {
            $base = rtrim(config('filesystems.disks.s3.url') ?: env('AWS_URL', ''), '/');
            if ($base && str_starts_with($state, $base . '/')) {
                return ltrim(substr($state, strlen($base . '/')), '/');
            }
            $path = ltrim(parse_url($state, PHP_URL_PATH) ?: '', '/');
            return $path ?: $state;
        }

        return $state;
    }

    // Always array for the component; scalar saved for single
    protected function coerceStateForComponent($state): array
    {
        if ($this->isMultiple()) {
            return is_array($state) ? $state : array_filter([$state]);
        }
        if (is_array($state)) return $state;
        if ($state === null || $state === '') return [];
        return [$state];
    }

    protected function guessMime(?string $ext): string
    {
        $ext = strtolower((string) $ext);
        return match ($ext) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png'         => 'image/png',
            'gif'         => 'image/gif',
            'webp'        => 'image/webp',
            'pdf'         => 'application/pdf',
            default       => 'application/octet-stream',
        };
    }
}