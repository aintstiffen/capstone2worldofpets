<?php

namespace App\Filament\Resources\PetResource\Pages;

use App\Filament\Resources\PetResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreatePet extends CreateRecord
{
    protected static string $resource = PetResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // If an image was uploaded to the public disk, transfer it to B2
        if (!empty($data['image']) && is_string($data['image']) && str_starts_with($data['image'], 'livewire-tmp/')) {
            $tempPath = $data['image']; // e.g. livewire-tmp/filename.jpg
            $publicDisk = Storage::disk('public');
            if ($publicDisk->exists($tempPath)) {
                $filename = basename($tempPath);
                $finalPath = 'image/' . Str::uuid() . '-' . $filename; // destination path in B2 with uuid prefix
                $stream = $publicDisk->readStream($tempPath);
                if ($stream) {
                    Storage::disk('b2')->put($finalPath, stream_get_contents($stream), [
                        'visibility' => 'private',
                    ]);
                    if (is_resource($stream)) {
                        fclose($stream);
                    }
                    // Optionally delete local temp file
                    $publicDisk->delete($tempPath);
                    $data['image'] = $finalPath;
                }
            }
        }
        return $data;
    }
}
