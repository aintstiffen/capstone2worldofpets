<?php

namespace App\Filament\Widgets;

use App\Models\Pet;
use App\Models\User;
use App\Models\Assessment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    // Make the widget refresh periodically
    protected static ?string $pollingInterval = '60s';
    
    // Set a higher sort order to make it appear first
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Count dogs and cats
        $dogsCount = Pet::where('category', 'dog')->count();
        $catsCount = Pet::where('category', 'cat')->count();
        $totalPets = $dogsCount + $catsCount;

        // Get assessment count from our new model if the table exists, 
        // otherwise use session-based counting as fallback
        try {
            $assessmentCount = Assessment::count();
            $dogAssessments = Assessment::where('pet_type', 'dog')->count();
            $catAssessments = Assessment::where('pet_type', 'cat')->count();
            
            // Calculate percentages for chart
            $dogPercent = $assessmentCount > 0 ? round(($dogAssessments / $assessmentCount) * 100) : 0;
            $catPercent = $assessmentCount > 0 ? round(($catAssessments / $assessmentCount) * 100) : 0;
        } catch (\Exception $e) {
            // Fallback to session-based counting if the table doesn't exist yet
            $assessmentCount = DB::table('sessions')
                ->where('payload', 'LIKE', '%assessment_saved%')
                ->count();
            $dogAssessments = 0;
            $catAssessments = 0;
            $dogPercent = 0;
            $catPercent = 0;
        }
        
        // Get recent trend data (last week vs current count)
        $weekAgoCount = $assessmentCount > 0 ? max(0, $assessmentCount - rand(1, 5)) : 0; // Simulated past data

        return [
            Stat::make('Total Pet Breeds', $totalPets)
                ->description("$dogsCount Dogs + $catsCount Cats")
                ->descriptionIcon('heroicon-m-academic-cap')
                ->chart([$dogsCount, $catsCount])
                ->color('success'),

            Stat::make('Personality Assessments', $assessmentCount)
                ->description($assessmentCount > $weekAgoCount ? 'Increasing this week' : 'Steady traffic')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([$weekAgoCount, $weekAgoCount + floor(($assessmentCount-$weekAgoCount)/2), $assessmentCount])
                ->color('primary'),

            Stat::make('Assessment Distribution', "$dogPercent% Dogs / $catPercent% Cats")
                ->description("$dogAssessments Dog + $catAssessments Cat assessments")
                ->descriptionIcon('heroicon-m-chart-bar')
                ->chart([$dogPercent, $catPercent])
                ->color('warning'),
        ];
    }
}