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
        // Normalize image to a full S3 URL
        if (!empty($data['image']) && !str_starts_with($data['image'], 'http')) {
            $base = rtrim(config('filesystems.disks.s3.url') ?: ("https://".config('filesystems.disks.s3.bucket').".s3.".config('filesystems.disks.s3.region').".amazonaws.com"), '/');
            $data['image'] = $base.'/'.ltrim($data['image'], '/');
        }

        return $data;
    }
}
