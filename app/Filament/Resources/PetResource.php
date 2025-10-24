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
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;


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

                // Per-color image uploads (name + file). Stored as JSON array of items
                // e.g. [{"name":"Black","image":"color_images/black.jpg"}, ...]
                Forms\Components\Repeater::make('color_images')
                    ->label('Color Images')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Color Name')
                            ->required()
                            ->maxLength(100),

                        Forms\Components\FileUpload::make('image')
                            ->label('Image')
                            ->image()
                            ->directory('color_images')
                            ->disk('s3')
                            ->visibility('public')
                            ->preserveFilenames()
                            ->helperText('Upload an image that represents this color. Files will be stored on your configured S3 bucket.'),
                    ])
                    ->columns(2)
                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                    ->collapsible()
                    ->reorderable()
                    ->helperText('Upload an image for each common color. The frontend will show these when clicking a color badge.')
                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                        return $data;
                    })
                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                        return $data;
                    }),

                // Gallery image URLs for carousel (up to 10 images)
                Forms\Components\Repeater::make('gallery')
                    ->label('Gallery Images (up to 10)')
                    ->schema([
                        Forms\Components\TextInput::make('url')
                            ->label('Image URL')
                            ->url()
                            ->required()
                            ->helperText('Paste a direct image URL from TheCatAPI or elsewhere.')
                    ])
                    ->maxItems(10)
                    ->columns(1)
                    ->helperText('These images will appear in the breed carousel. You can add up to 10.'),

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
                        // When a breed is chosen, fetch full details and autofill form fields
                        $type = $get('category');
                        if (!$type || !$state) return;

                        try {
                            // Show loading notification
                            \Filament\Notifications\Notification::make()
                                ->title('Fetching breed details...')
                                ->info()
                                ->send();
                            
                            $breedData = null;
                            
                            if ($type === 'dog') {
                                // Fetch breed details from TheDogAPI using breeds endpoint
                                $breedResp = Http::withHeaders([
                                    'x-api-key' => config('services.dog_api.key'),
                                ])->get(config('services.dog_api.base_url') . '/breeds', [
                                    'attach_breed' => 0
                                ]);
                                
                                if ($breedResp->ok()) {
                                    $breeds = $breedResp->json();
                                    // Find the breed by ID
                                    foreach ($breeds as $breed) {
                                        if ($breed['id'] == $state) {
                                            $breedData = $breed;
                                            break;
                                        }
                                    }
                                }
                                
                                // Fetch an image for the breed
                                $imageResp = Http::withHeaders([
                                    'x-api-key' => config('services.dog_api.key'),
                                ])->get(config('services.dog_api.base_url') . '/images/search', [
                                    'breed_ids' => $state,
                                    'limit' => 1,
                                ]);
                                
                                if ($imageResp->ok() && !empty($imageResp->json())) {
                                    $imageData = $imageResp->json()[0] ?? [];
                                    $url = $imageData['url'] ?? null;
                                    if ($url) {
                                        $set('image', $url);
                                    }
                                }
                            } else {
                                // Fetch breed details from TheCatAPI using breeds endpoint
                                $breedResp = Http::withHeaders([
                                    'x-api-key' => config('services.cat_api.key'),
                                ])->get(config('services.cat_api.base_url') . '/breeds', [
                                    'attach_breed' => 0
                                ]);
                                
                                if ($breedResp->ok()) {
                                    $breeds = $breedResp->json();
                                    // Find the breed by ID
                                    foreach ($breeds as $breed) {
                                        if ($breed['id'] == $state) {
                                            $breedData = $breed;
                                            break;
                                        }
                                    }
                                }
                                
                                // Fetch an image for the breed
                                $imageResp = Http::withHeaders([
                                    'x-api-key' => config('services.cat_api.key'),
                                ])->get(config('services.cat_api.base_url') . '/images/search', [
                                    'breed_ids' => $state,
                                    'limit' => 1,
                                ]);
                                
                                if ($imageResp->ok() && !empty($imageResp->json())) {
                                    $imageData = $imageResp->json()[0] ?? [];
                                    $url = $imageData['url'] ?? null;
                                    if ($url) {
                                        $set('image', $url);
                                    }
                                }
                            }
                            
                            // Autofill form fields from breed data
                            if ($breedData) {
                                // Name and slug
                                if (!empty($breedData['name'])) {
                                    $set('name', $breedData['name']);
                                    $set('slug', \Illuminate\Support\Str::slug($breedData['name']));
                                }
                                
                                // Temperament
                                if (!empty($breedData['temperament'])) {
                                    $set('temperament', $breedData['temperament']);
                                }
                                
                                // Lifespan
                                if (!empty($breedData['life_span'])) {
                                    $set('lifespan', $breedData['life_span']);
                                }
                                
                                // Cat-specific ratings (1-5 scale)
                                if ($type === 'cat') {
                                    // Energy level
                                    if (isset($breedData['energy_level'])) {
                                        $energyMap = [1 => 'Low', 2 => 'Low', 3 => 'Medium', 4 => 'High', 5 => 'High'];
                                        $set('energy', $energyMap[$breedData['energy_level']] ?? 'Medium');
                                    }
                                    
                                    if (isset($breedData['affection_level'])) {
                                        $set('friendliness', $breedData['affection_level']);
                                    }
                                    
                                    if (isset($breedData['intelligence'])) {
                                        $set('trainability', $breedData['intelligence']);
                                    }
                                    
                                    if (isset($breedData['energy_level'])) {
                                        $set('exerciseNeeds', $breedData['energy_level']);
                                    }
                                    
                                    if (isset($breedData['grooming'])) {
                                        $set('grooming', $breedData['grooming']);
                                    }
                                    
                                    // Size from weight
                                    if (!empty($breedData['weight']['imperial'])) {
                                        $weight = $breedData['weight']['imperial'];
                                        preg_match('/(\d+)/', $weight, $matches);
                                        $avgWeight = isset($matches[1]) ? (int)$matches[1] : 0;
                                        
                                        if ($avgWeight < 8) {
                                            $set('size', 'Small');
                                        } elseif ($avgWeight < 15) {
                                            $set('size', 'Medium');
                                        } else {
                                            $set('size', 'Large');
                                        }
                                    }
                                }
                                
                                // Dog-specific fields
                                if ($type === 'dog') {
                                    // Set default ratings for dogs (APIs don't provide these)
                                    $set('friendliness', 4);
                                    $set('trainability', 3);
                                    $set('exerciseNeeds', 3);
                                    $set('grooming', 3);
                                    $set('energy', 'Medium');
                                    
                                    // Size from weight
                                    if (!empty($breedData['weight']['imperial'])) {
                                        $weight = $breedData['weight']['imperial'];
                                        preg_match('/(\d+)/', $weight, $matches);
                                        $avgWeight = isset($matches[1]) ? (int)$matches[1] : 0;
                                        
                                        if ($avgWeight < 25) {
                                            $set('size', 'Small');
                                        } elseif ($avgWeight < 60) {
                                            $set('size', 'Medium');
                                        } else {
                                            $set('size', 'Large');
                                        }
                                    }
                                }
                            }
                            
                            // Show success notification
                            Notification::make()
                                ->title('Breed details loaded!')
                                ->body('Form fields have been automatically filled with breed information.')
                                ->success()
                                ->send();
                                
                        } catch (\Throwable $e) {
                            // Log error for debugging
                            \Log::error('Breed fetch error: ' . $e->getMessage(), [
                                'breed_id' => $state,
                                'type' => $type
                            ]);
                            
                            // Show error notification
                            Notification::make()
                                ->title('Failed to fetch breed details')
                                ->body('Error: ' . $e->getMessage() . '. Please fill in the fields manually.')
                                ->danger()
                                ->send();
                        }
                    })
                    
                    ->dehydrated(false),

                Repeater::make('diet_images')
                    ->label('Common Diet')
                    ->schema([
                        TextInput::make('name')
                            ->label('Diet Name')
                            ->required()
                            ->columnSpan(6),

                        FileUpload::make('image')
                            ->label('Image')
                            ->required()
                            ->image()
                            ->disk(env('FILESYSTEM_DISK', 's3'))
                            ->directory('diet_images')
                            ->visibility('public')
                            ->preserveFilenames()
                            ->columnSpan(6)
                           
                    ])
                    ->createItemButtonLabel('Add diet item')
                    ->columns(2)
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                    ->reorderable()
                    ->helperText('Upload an image for each common diet item; the frontend will show a hover preview.')
                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                        return $data;
                    })
                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                        return $data;
                    }),

                Forms\Components\Select::make('gif_url')
                    ->label('Select a GIF (Tenor)')
                    ->searchable()
                    ->reactive()
                    ->options(function (callable $get) {
                        try {
                            $query = $get('name') ?? 'cute pet';
                            $response = Http::get('https://tenor.googleapis.com/v2/search', [
                                'key' => config('services.tenor.key'),
                                'q' => $query,
                                'limit' => 10,
                                'contentfilter' => 'high',
                            ]);

                            if ($response->ok()) {
                                $results = $response->json()['results'] ?? [];

                                return collect($results)->mapWithKeys(function ($item) {
                                    $media = $item['media_formats'] ?? [];
                                    $url = $media['gif']['url'] 
                                        ?? $media['mediumgif']['url'] 
                                        ?? $media['mp4']['url'] 
                                        ?? $media['tinygif']['url'] 
                                        ?? $media['nanogif']['url'] 
                                        ?? null;

                                    if (! $url) {
                                        return [];
                                    }

                                    $label = $item['content_description'] ?? ($item['title'] ?? 'GIF');
                                    return [$url => $label];
                                })->filter()->toArray();
                            }
                        } catch (\Exception $e) {
                            \Log::error('Tenor API failed: ' . $e->getMessage());
                        }

                        return [];
                    })
                    ->helperText('Search Tenor and select a GIF URL to store on the pet record')
                    ->getSearchResultsUsing(function (string $search) {
                        try {
                            $response = Http::get('https://tenor.googleapis.com/v2/search', [
                                'key' => config('services.tenor.key'),
                                'q' => $search,
                                'limit' => 10,
                                'contentfilter' => 'high',
                            ]);

                            if ($response->ok()) {
                                $results = $response->json()['results'] ?? [];

                                return collect($results)->mapWithKeys(function ($item) {
                                    $media = $item['media_formats'] ?? [];
                                    $url = $media['gif']['url'] 
                                        ?? $media['mediumgif']['url'] 
                                        ?? $media['mp4']['url'] 
                                        ?? $media['tinygif']['url'] 
                                        ?? $media['nanogif']['url'] 
                                        ?? null;

                                    if (! $url) {
                                        return [];
                                    }

                                    $label = $item['content_description'] ?? ($item['title'] ?? 'GIF');
                                    return [$url => $label];
                                })->filter()->toArray();
                            }
                        } catch (\Exception $e) {
                            \Log::error('Tenor search failed: ' . $e->getMessage());
                        }

                        return [];
                    }),

                Forms\Components\TextInput::make('image')
                    ->label('Image URL')
                    ->required()
                    ->url()
                    ->live(onBlur: true)
                    ->reactive()
                    ->helperText('This stores a direct image URL from the API'),

                Forms\Components\Textarea::make('description')
                    ->required()
                    ->rows(8)
                    ->placeholder('Provide a comprehensive description of this pet breed')
                    ->helperText('Enter the full description manually. No length limit is enforced by the form.'),

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
                    ->extraImgAttributes(['loading' => 'lazy']),

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