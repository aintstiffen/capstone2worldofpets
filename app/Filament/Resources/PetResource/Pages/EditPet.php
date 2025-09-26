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
            $publicDisk = Storage::disk('public');
            $b2Disk = Storage::disk('b2');
            $tmpPath = $data['image'];
            if ($publicDisk->exists($tmpPath)) {
                $filename = basename($tmpPath);
                $b2Path = 'image/' . Str::uuid() . '-' . $filename;
                $stream = $publicDisk->readStream($tmpPath);
                if ($stream && $b2Disk->put($b2Path, $stream)) {
                    if (is_resource($stream)) fclose($stream);
                    $publicDisk->delete($tmpPath);
                    $data['image'] = $b2Path;
                } else {
                    if (is_resource($stream)) fclose($stream);
                }
            }
        }
        return $data;
    }
}
