<?php

namespace App\Filament\Resources\PetResource\Pages;

use App\Filament\Resources\PetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EditPet extends EditRecord
{
    protected static string $resource = PetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['image']) && is_string($data['image']) && str_starts_with($data['image'], 'livewire-tmp/')) {
            try {
                $publicDisk = Storage::disk('public');
                $b2Disk = Storage::disk('b2');
                $tmpPath = $data['image'];
                
                if ($publicDisk->exists($tmpPath)) {
                    $filename = basename($tmpPath);
                    $b2Path = 'image/' . Str::uuid() . '-' . $filename;
                    
                    // Get file content directly instead of using stream
                    $fileContent = $publicDisk->get($tmpPath);
                    
                    if ($fileContent !== false) {
                        $uploadSuccess = $b2Disk->put($b2Path, $fileContent, [
                            'visibility' => 'private',
                        ]);
                        
                        if ($uploadSuccess) {
                            // Clean up temp file
                            $publicDisk->delete($tmpPath);
                            $data['image'] = $b2Path;
                        } else {
                            \Log::error('Failed to upload file to B2 during edit', ['tmpPath' => $tmpPath, 'b2Path' => $b2Path]);
                        }
                    } else {
                        \Log::error('Failed to read temp file content during edit', ['tmpPath' => $tmpPath]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Error transferring image to B2 during edit: ' . $e->getMessage(), [
                    'tmpPath' => $data['image'] ?? 'unknown',
                    'exception' => $e->getTraceAsString()
                ]);
            }
        }
        return $data;
    }
}
