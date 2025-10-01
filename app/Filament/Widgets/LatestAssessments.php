<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestAssessments extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Recent Personality Assessments')
            ->description('Latest assessments completed by users')
            ->query(
                Assessment::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pet_type')
                    ->label('Pet Type')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'dog' => 'success',
                        'cat' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->default('Guest'),
            ]);
    }
}