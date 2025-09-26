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
            try {
                $tempPath = $data['image']; // e.g. livewire-tmp/filename.jpg
                $publicDisk = Storage::disk('public');
                $b2Disk = Storage::disk('b2');
                
                if ($publicDisk->exists($tempPath)) {
                    $filename = basename($tempPath);
                    $finalPath = 'image/' . Str::uuid() . '-' . $filename; // destination path in B2 with uuid prefix
                    
                    // Get file content directly instead of using stream
                    $fileContent = $publicDisk->get($tempPath);
                    
                    if ($fileContent !== false) {
                        // Upload to B2
                        $uploadSuccess = $b2Disk->put($finalPath, $fileContent, [
                            'visibility' => 'private',
                        ]);
                        
                        if ($uploadSuccess) {
                            // Clean up temp file
                            $publicDisk->delete($tempPath);
                            $data['image'] = $finalPath;
                        } else {
                            \Log::error('Failed to upload file to B2', ['tempPath' => $tempPath, 'finalPath' => $finalPath]);
                        }
                    } else {
                        \Log::error('Failed to read temp file content', ['tempPath' => $tempPath]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Error transferring image to B2: ' . $e->getMessage(), [
                    'tempPath' => $data['image'] ?? 'unknown',
                    'exception' => $e->getTraceAsString()
                ]);
            }
        }
        return $data;
    }
}
