<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PetResource\Pages;
use App\Models\Pet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class PetResource extends Resource
{
    protected static ?string $model = Pet::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->nullable()
                ->maxLength(65535),

            Forms\Components\FileUpload::make('image')
                ->image()
                ->required()
                ->disk('b2')                        // ✅ Use Backblaze B2
                ->directory('pets')                 // ✅ Store under "pets/" folder
                ->visibility('public')              // ✅ Publicly accessible
                ->maxSize(5120)                     // 5MB limit
                ->imagePreviewHeight('200')
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                ->helperText('Upload a clear, high-quality image. Maximum size: 5MB'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->limit(50),

                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->circular()
                    ->defaultImageUrl('/placeholder.svg?height=200&width=200')
                    ->url(fn ($record) =>
                        $record->image
                            ? Storage::disk('b2')->url($record->image) // ✅ Generate proper B2 URL
                            : null
                    )
                    ->extraImgAttributes(['loading' => 'lazy']),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPets::route('/'),
            'create' => Pages\CreatePet::route('/create'),
            'edit' => Pages\EditPet::route('/{record}/edit'),
        ];
    }
}
