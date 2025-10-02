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
        if (!empty($data['image']) && is_string($data['image'])) {
            $base = rtrim(config('filesystems.disks.s3.url') ?: env('AWS_URL', ''), '/') . '/';
            if (str_starts_with($data['image'], 'http')) {
                $data['image'] = ltrim(str_replace($base, '', $data['image']), '/');
            }
        }
        return $data;
    }
}
