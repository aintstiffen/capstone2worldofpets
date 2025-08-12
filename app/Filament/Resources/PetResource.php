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
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // auto-fill slug while typing (editable)
                        $set('slug', Str::slug($state));
                    })
                    ->maxLength(255),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\Select::make('category')
                    ->options([
                        'dog' => 'Dog',
                        'cat' => 'Cat',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('size')->maxLength(255),
                Forms\Components\TextInput::make('temperament')->maxLength(255),
                Forms\Components\TextInput::make('lifespan')->maxLength(255),
                Forms\Components\TextInput::make('energy')->maxLength(255),

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

                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('pets')
                    ->maxSize(2048) // KB (2MB)
                    ->imagePreviewHeight('150'),

                Forms\Components\Textarea::make('description')->rows(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                ->label('Photo')
                ->disk('public') // Storage disk used
                ->height(40)     // Thumbnail height
                ->width(40)      // Thumbnail width
                ->circular(),    // Optional: makes it round
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
