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
        if (!empty($data['image']) && !str_starts_with($data['image'], 'http')) {
            $base = rtrim(config('filesystems.disks.s3.url') ?: ("https://".config('filesystems.disks.s3.bucket').".s3.".config('filesystems.disks.s3.region').".amazonaws.com"), '/');
            $data['image'] = $base.'/'.ltrim($data['image'], '/');
        }

        return $data;
    }
}
