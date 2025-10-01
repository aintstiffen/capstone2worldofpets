<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssessmentResource\Pages;
use App\Models\Assessment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Assessments';

    protected static ?string $navigationGroup = 'Analytics';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('User')
                    ->nullable(),
                Forms\Components\Select::make('pet_type')
                    ->options([
                        'dog' => 'Dog',
                        'cat' => 'Cat',
                    ])
                    ->required(),
                Forms\Components\DateTimePicker::make('created_at')
                    ->label('Assessment Date')
                    ->disabled()
                    ->dehydrated(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->placeholder('Guest'),
                Tables\Columns\TextColumn::make('pet_type')
                    ->label('Pet Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'dog' => 'success',
                        'cat' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Assessment Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('pet_type')
                    ->options([
                        'dog' => 'Dog',
                        'cat' => 'Cat',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListAssessments::route('/'),
            'view' => Pages\ViewAssessment::route('/{record}'),
        ];
    }
}