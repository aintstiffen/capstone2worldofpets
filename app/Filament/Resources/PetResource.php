<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PetResource\Pages;
use App\Models\Pet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\Http;

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
                    ->label('Pet Type')
                    ->reactive(),

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

                // Replace upload with external image URL resolved from breed API

                Forms\Components\Select::make('breed_lookup')
                    ->label('Breed (search from API)')
                    ->searchable()
                    ->reactive()
                    ->helperText('Start typing to search breeds from your selected Pet Type')
                    ->getSearchResultsUsing(function (string $search, $get) {
                        $type = $get('category');
                        if (!$type) return [];

                        try {
                            if ($type === 'dog') {
                                $resp = Http::withHeaders([
                                    'x-api-key' => config('services.dog_api.key'),
                                ])->get(config('services.dog_api.base_url') . '/breeds/search', [
                                    'q' => $search,
                                ]);
                            } else {
                                $resp = Http::withHeaders([
                                    'x-api-key' => config('services.cat_api.key'),
                                ])->get(config('services.cat_api.base_url') . '/breeds/search', [
                                    'q' => $search,
                                ]);
                            }
                        } catch (\Throwable $e) {
                            return [];
                        }

                        if (!$resp->ok()) return [];

                        $results = [];
                        foreach ($resp->json() as $item) {
                            // Use unique id as value, show name
                            $results[$item['id']] = $item['name'] ?? $item['id'];
                        }
                        return $results;
                    })
                    ->afterStateUpdated(function ($state, $set, $get) {
                        // When a breed is chosen, fetch an image for it and set image field to URL
                        $type = $get('category');
                        if (!$type || !$state) return;

                        try {
                            if ($type === 'dog') {
                                // TheDogAPI: images search by breed id
                                $resp = Http::withHeaders([
                                    'x-api-key' => config('services.dog_api.key'),
                                ])->get(config('services.dog_api.base_url') . '/images/search', [
                                    'breed_ids' => $state,
                                    'limit' => 1,
                                ]);
                            } else {
                                // TheCatAPI
                                $resp = Http::withHeaders([
                                    'x-api-key' => config('services.cat_api.key'),
                                ])->get(config('services.cat_api.base_url') . '/images/search', [
                                    'breed_ids' => $state,
                                    'limit' => 1,
                                ]);
                            }

                            if ($resp->ok() && !empty($resp->json())) {
                                $payload = $resp->json()[0] ?? [];
                                $url = $payload['url'] ?? null;
                                if ($url) {
                                    $set('image', $url);
                                }
                                // Try to fill name/slug from the returned breed details if not already set
                                $breeds = $payload['breeds'] ?? [];
                                $currentName = $get('name');
                                if (empty($currentName) && !empty($breeds) && !empty($breeds[0]['name'])) {
                                    $name = $breeds[0]['name'];
                                    $set('name', $name);
                                    $set('slug', \Illuminate\Support\Str::slug($name));
                                }
                            }
                        } catch (\Throwable $e) {
                            // Ignore errors silently
                        }
                    })
                    ->dehydrated(false),

                Forms\Components\TextInput::make('image')
                    ->label('Image URL')
                    ->required()
                    ->url()
                    ->helperText('This stores a direct image URL from the API'),

                Forms\Components\Textarea::make('description')
                    ->required()
                    ->minLength(50)
                    ->maxLength(1000)
                    ->rows(4)
                    ->placeholder('Provide a comprehensive description of this pet breed')
                    ->helperText('Include origin, history, common uses, and distinguishing characteristics'),

                // Image Preview Section
                Forms\Components\Section::make('Image Preview')
                    ->schema([
                        Forms\Components\ViewField::make('image_preview')
                            ->view('filament.components.image-preview')
                            ->label('Preview Image (Click on areas to add fun facts)')
                            ->helperText('Click on different parts of the image (ears, eyes, nose, etc.) to add fun facts'),
                    ])
                    ->collapsed()
                    ->visible(fn ($get) => !empty($get('image'))),

                // Hotspots & Fun Facts Management
                Forms\Components\Section::make('Interactive Hotspots & Fun Facts')
                    ->description('Add clickable areas on the image with interesting facts. Click on the image preview above to add hotspots.')
                    ->schema([
                        Forms\Components\Repeater::make('hotspots')
                            ->label('Hotspots')
                            ->schema([
                                Forms\Components\Select::make('feature')
                                    ->label('Feature')
                                    ->options([
                                        'ears' => 'Ears',
                                        'eyes' => 'Eyes',
                                        'nose' => 'Nose',
                                        'tail' => 'Tail',
                                        'paws' => 'Paws',
                                        'coat' => 'Coat',
                                        'whiskers' => 'Whiskers',
                                        'mouth' => 'Mouth',
                                    ])
                                    ->required()
                                    ->distinct(),
                                Forms\Components\TextInput::make('position_x')
                                    ->label('Position X (%)')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->suffix('%'),
                                Forms\Components\TextInput::make('position_y')
                                    ->label('Position Y (%)')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->suffix('%'),
                                Forms\Components\TextInput::make('width')
                                    ->label('Width (px)')
                                    ->numeric()
                                    ->default(40)
                                    ->required()
                                    ->minValue(10)
                                    ->maxValue(200),
                                Forms\Components\TextInput::make('height')
                                    ->label('Height (px)')
                                    ->numeric()
                                    ->default(40)
                                    ->required()
                                    ->minValue(10)
                                    ->maxValue(200),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['feature'] ?? null)
                            ->reorderable()
                            ->defaultItems(0),

                        Forms\Components\Repeater::make('fun_facts')
                            ->label('Fun Facts')
                            ->schema([
                                Forms\Components\Select::make('feature')
                                    ->label('Feature')
                                    ->options([
                                        'ears' => 'Ears',
                                        'eyes' => 'Eyes',
                                        'nose' => 'Nose',
                                        'tail' => 'Tail',
                                        'paws' => 'Paws',
                                        'coat' => 'Coat',
                                        'whiskers' => 'Whiskers',
                                        'mouth' => 'Mouth',
                                    ])
                                    ->required()
                                    ->distinct()
                                    ->helperText('Should match a hotspot feature'),
                                Forms\Components\Textarea::make('fact')
                                    ->label('Fun Fact')
                                    ->required()
                                    ->rows(2)
                                    ->maxLength(500)
                                    ->placeholder('Enter an interesting fact about this feature'),
                            ])
                            ->columns(1)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['feature'] ?? null)
                            ->reorderable()
                            ->defaultItems(0),
                    ])
                    ->collapsed(),
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
                    ->getStateUsing(fn($record) => $record->image_url ?? $record->image)
                    ->extraImgAttributes(['loading' => 'lazy'])
                    ,

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
