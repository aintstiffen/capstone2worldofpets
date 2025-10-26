<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PetResource\Pages;
use App\Models\Pet;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

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

                // Main image upload
                FileUpload::make('image')
                    ->label('Main Image')
                    ->image()
                    ->disk(env('FILESYSTEM_DISK', 's3'))
                    ->directory('images')
                    ->visibility('public')
                    ->preserveFilenames()
                    ->helperText('Upload the main breed image shown in lists and previews.'),

                Forms\Components\TextInput::make('average_weight')
                    ->required()
                    ->placeholder('e.g., 10-20 kg')
                    ->helperText('Average weight range (include units, e.g., kg or lbs). Use a consistent format.')
                    ->maxLength(100),

                Forms\Components\TextInput::make('price_range')
                    ->required()
                    ->placeholder('e.g., $500 - $1500')
                    ->helperText('Typical price range for this breed. Include currency.')
                    ->maxLength(255),

                Forms\Components\TextInput::make('lifespan')
                    ->required()
                    ->placeholder('10-15')
                    ->helperText('Average lifespan in years (range)')
                    ->maxLength(50),

                Forms\Components\TextInput::make('energy_level')
                    ->required()
                    ->placeholder('High, Medium, Low')
                    ->helperText('Overall energy level')
                    ->maxLength(100),

                Forms\Components\TextInput::make('friendliness')
                    ->placeholder('e.g., 3 or Friendly')
                    ->helperText('Numeric (1-5) or short descriptor (Friendly, Reserved).')
                    ->maxLength(50),

                Forms\Components\TextInput::make('origin')
                    ->label('Origin')
                    ->required()
                    ->placeholder('Country or region of origin')
                    ->helperText('Where the breed originated (country or region).')
                    ->maxLength(100),


                Forms\Components\TextInput::make('grooming')
                    ->placeholder('e.g., Low, Moderate, High or 1-5')
                    ->helperText('Grooming frequency/effort; numeric (1-5) or short descriptor.')
                    ->maxLength(50),

                // Color Images with Upload
                Repeater::make('color_images')
                    ->label('Color Images')
                    ->schema([
                        TextInput::make('name')
                            ->label('Color Name')
                            ->required()
                            ->placeholder('e.g., Black, White, Grey')
                            ->maxLength(100)
                            ->columnSpan(6),

                        FileUpload::make('image')
                            ->label('Image')
                            ->required()
                            ->image()
                            ->disk(env('FILESYSTEM_DISK', 's3'))
                            ->directory('color_images')
                            ->visibility('public')
                            ->preserveFilenames()
                            ->helperText('Upload an image that represents this color.')
                            ->columnSpan(6),
                    ])
                    ->createItemButtonLabel('Add color variation')
                    ->columns(2)
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                    ->reorderable()
                    ->helperText('Upload an image for each color variation. The frontend will show a hover preview.')
                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                        return $data;
                    })
                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                        return $data;
                    }),

                Repeater::make('gallery')
                    ->label('Gallery Images (up to 10)')
                    ->schema([
                        FileUpload::make('url')  // ← Changed from 'image' to 'url'
                            ->label('Gallery Image')
                            ->required()
                            ->image()
                            ->disk(env('FILESYSTEM_DISK', 's3'))
                            ->directory('gallery_images')
                            ->visibility('public')
                            ->preserveFilenames()
                            ->helperText('Upload a gallery image. It will be stored on S3.'),
                    ])
                    ->createItemButtonLabel('Add gallery image')
                    ->maxItems(10)
                    ->columns(1)
                    ->collapsible()
                    ->reorderable()
                    ->defaultItems(0)
                    ->helperText('Upload up to 10 images for the breed gallery carousel.'),

                
                Repeater::make('diet_images')
                    ->label('Common Diet')
                    ->schema([
                        TextInput::make('name')
                            ->label('Diet Name')
                            ->required()
                            ->placeholder('e.g., Dry Food, Wet Food, Raw')
                            ->columnSpan(6),

                        FileUpload::make('image')
                            ->label('Image')
                            ->required()
                            ->image()
                            ->disk(env('FILESYSTEM_DISK', 's3'))
                            ->directory('diet_images')
                            ->visibility('public')
                            ->preserveFilenames()
                            ->columnSpan(6),
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
                            \Log::error('Tenor API failed: '.$e->getMessage());
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
                            \Log::error('Tenor search failed: '.$e->getMessage());
                        }

                        return [];
                    }),

            
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
                    ->visible(fn ($get) => ! empty($get('image'))),

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
                    ->getStateUsing(fn ($record) => $record->image_url ?? $record->image)
                    ->extraImgAttributes(['loading' => 'lazy']),

                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category')->sortable(),
                Tables\Columns\TextColumn::make('average_weight')->sortable(),
                Tables\Columns\TextColumn::make('friendliness'),
                Tables\Columns\TextColumn::make('origin'),
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
