<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\PetResource\Pages;
use App\Models\Pet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Tables\Columns\ImageColumn;

class PetResource extends Resource
{
    protected static ?string $model = Pet::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->minLength(2)
                    ->maxLength(100)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('slug', Str::slug($state));
                    })
                    ->placeholder('Enter pet breed name')
                    ->label('Breed Name'),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(100)
                    ->placeholder('breed-name-slug')
                    ->helperText('URL-friendly version of the name'),

                Forms\Components\Select::make('category')
                    ->options([
                        'dog' => 'Dog',
                        'cat' => 'Cat',
                    ])
                    ->required()
                    ->label('Pet Type'),

                Forms\Components\TextInput::make('size')
                    ->required()
                    ->placeholder('Small, Medium, Large')
                    ->helperText('Use consistent sizing: Small, Small to Medium, Medium, Medium to Large, Large')
                    ->maxLength(100),

                Forms\Components\TextInput::make('temperament')
                    ->required()
                    ->placeholder('Friendly, Intelligent, Active')
                    ->helperText('List key traits separated by commas')
                    ->maxLength(255),

                Forms\Components\TextInput::make('lifespan')
                    ->required()
                    ->placeholder('10-15')
                    ->helperText('Average lifespan in years (range)')
                    ->maxLength(50),

                Forms\Components\TextInput::make('energy')
                    ->required()
                    ->placeholder('High, Medium, Low')
                    ->helperText('Overall energy level')
                    ->maxLength(100),

                Forms\Components\Select::make('friendliness')
                    ->options([1, 2, 3, 4, 5])
                    ->default(3),

                Forms\Components\Select::make('trainability')
                    ->options([1, 2, 3, 4, 5])
                    ->default(3),

                Forms\Components\Select::make('exerciseNeeds')
                    ->options([1, 2, 3, 4, 5])
                    ->default(3),

                Forms\Components\Select::make('grooming')
                    ->options([1, 2, 3, 4, 5])
                    ->default(3),

                Forms\Components\TagsInput::make('colors'),

                // ✅ Upload directly to Cloudflare R2
                FileUpload::make('image')
                    ->disk('r2')
                    ->directory('pets')
                    ->image()
                    ->imageEditor()
                    ->required()
                    // Add custom URL generator for previews that uses the Public Development URL
                    ->previewable(true)
                     ->visibility('public')
                        
                    ->previewable(),

                Forms\Components\Textarea::make('description')
                    ->required()
                    ->minLength(50)
                    ->maxLength(1000)
                    ->rows(4)
                    ->placeholder('Provide a comprehensive description of this pet breed')
                    ->helperText('Include origin, history, common uses, and distinguishing characteristics'),

                // Sections unchanged (Hotspots, Fun Facts)...
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->circular()
                    ->defaultImageUrl('/placeholder.svg?height=200&width=200')
                    ->getStateUsing(function ($record) {
                        if (!$record->image) {
                            return null;
                        }
                        // Always use the Public Development URL, never the direct R2 storage URL
                        return 'https://pub-1c70e1b6445e4076a7225a0b45b8bf3b.r2.dev/' . ltrim($record->image, '/');
                    })
                    ->extraImgAttributes(['loading' => 'lazy'])
                    // Keep using r2 disk for uploads
                    ->disk('r2'),

                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category')->sortable(),
                Tables\Columns\TextColumn::make('size')->sortable(),
                Tables\Columns\TextColumn::make('friendliness'),
                Tables\Columns\TextColumn::make('trainability'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
