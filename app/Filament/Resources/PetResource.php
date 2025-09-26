<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PetResource\Pages;
use App\Filament\Resources\PetResource\RelationManagers;
use App\Models\Pet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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
                        // auto-fill slug while typing (editable)
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
                    ->options([
                        1 => '1',
                        2 => '2',
                        3 => '3',
                        4 => '4',
                        5 => '5',
                    ])
                    ->default(3),

                Forms\Components\Select::make('trainability')
                    ->options([
                        1 => '1',
                        2 => '2',
                        3 => '3',
                        4 => '4',
                        5 => '5',
                    ])
                    ->default(3),

                Forms\Components\Select::make('exerciseNeeds')
                    ->options([
                        1 => '1',
                        2 => '2',
                        3 => '3',
                        4 => '4',
                        5 => '5',
                    ])
                    ->default(3),

                Forms\Components\Select::make('grooming')
                    ->options([
                        1 => '1',
                        2 => '2',
                        3 => '3',
                        4 => '4',
                        5 => '5',
                    ])
                    ->default(3),

                Forms\Components\TagsInput::make('colors'),

                // inside PetResource::form(...)
            Forms\Components\FileUpload::make('image')
                ->image()
                ->required()
                ->disk('b2')               // -> store directly on Backblaze B2
                ->directory('pets')        // -> stored path will be like "pets/filename.jpg"
                ->visibility('private')    // optional: explicitly mark private
                ->maxSize(5120)
                ->imagePreviewHeight('200')
                ->acceptedFileTypes(['image/jpeg','image/png','image/gif','image/webp'])
                ->helperText('Upload a clear image. Max 5MB'),

                Forms\Components\Textarea::make('description')
                    ->required()
                    ->minLength(50)
                    ->maxLength(1000)
                    ->rows(4)
                    ->placeholder('Provide a comprehensive description of this pet breed')
                    ->helperText('Include origin, history, common uses, and distinguishing characteristics'),
                
                // Hotspot Editor Section
                Forms\Components\Section::make('Interactive Hotspots')
                    ->description('Define positions for interactive tooltips on the pet image')
                    ->collapsed()
                    ->schema([
                        Forms\Components\Repeater::make('hotspots')
                            ->schema([
                                Forms\Components\Select::make('feature')
                                    ->options([
                                        'ears' => 'Ears',
                                        'eyes' => 'Eyes',
                                        'tail' => 'Tail',
                                        'paws' => 'Paws',
                                        'nose' => 'Nose',
                                        'coat' => 'Coat/Fur',
                                    ])
                                    ->required()
                                    ->helperText('Each feature can only have one hotspot'),
                                Forms\Components\TextInput::make('position_x')
                                    ->label('X Position (%)')
                                    ->helperText('Horizontal position (0-100%)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->step(1)
                                    ->required(),
                                Forms\Components\TextInput::make('position_y')
                                    ->label('Y Position (%)')
                                    ->helperText('Vertical position (0-100%)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->step(1)
                                    ->required(),
                                Forms\Components\TextInput::make('width')
                                    ->label('Width (px)')
                                    ->numeric()
                                    ->default(40)
                                    ->minValue(20)
                                    ->maxValue(100)
                                    ->step(1)
                                    ->required(),
                                Forms\Components\TextInput::make('height')
                                    ->label('Height (px)')
                                    ->numeric()
                                    ->default(40)
                                    ->minValue(20)
                                    ->maxValue(100)
                                    ->step(1)
                                    ->required(),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['feature'] ?? null),
                    ]),
                    
                Forms\Components\Section::make('Fun Facts')
                    ->description('Add interesting facts about this breed\'s features')
                    ->collapsed()
                    ->schema([
                        Forms\Components\Repeater::make('fun_facts')
                            ->schema([
                                Forms\Components\Select::make('feature')
                                    ->options([
                                        'ears' => 'Ears',
                                        'eyes' => 'Eyes',
                                        'tail' => 'Tail',
                                        'paws' => 'Paws',
                                        'nose' => 'Nose',
                                        'coat' => 'Coat/Fur',
                                    ])
                                    ->required()
                                    ->helperText('Each feature can only have one fun fact'),
                                Forms\Components\Textarea::make('fact')
                                    ->label('Fun Fact')
                                    ->required()
                                    ->placeholder('Share an interesting fact about this feature')
                                    ->helperText('Keep facts concise, informative, and under 200 characters')
                                    ->minLength(10)
                                    ->maxLength(200)
                                    ->rows(3),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['feature'] ?? null),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            Tables\Columns\ImageColumn::make('image')
            ->label('Image')
            ->circular()
            ->defaultImageUrl('/placeholder.svg?height=200&width=200')
            ->url(fn ($record) =>
                $record->image ? url('/uploads/' . $record->image) : null
            )
            ->extraImgAttributes(['loading' => 'lazy']),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category')->sortable(),
                Tables\Columns\TextColumn::make('size')->sortable(),
                Tables\Columns\TextColumn::make('friendliness'),
                Tables\Columns\TextColumn::make('trainability'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                //
            ])
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
        return [
            //
        ];
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
