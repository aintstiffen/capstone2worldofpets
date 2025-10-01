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
        // S3/B2 storage configuration has been removed
        // The file upload now uses the public disk directly as configured in PetResource
        
        return $data;
    }
}
