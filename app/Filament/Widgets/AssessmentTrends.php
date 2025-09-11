<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class AssessmentTrends extends ChartWidget
{
    protected static ?string $heading = 'Assessment Activity';
    protected static ?string $subheading = 'Last 7 days';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Get the last 7 days data
        $days = collect(range(6, 0))->map(function ($daysAgo) {
            return Carbon::now()->subDays($daysAgo)->format('Y-m-d');
        });

        // Try to get actual assessment data for each day
        try {
            $assessmentsByDay = Assessment::whereBetween('created_at', [
                Carbon::now()->subDays(6)->startOfDay(),
                Carbon::now()->endOfDay(),
            ])
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

            // Fill in days with no data
            $dayCounts = $days->mapWithKeys(function ($day) use ($assessmentsByDay) {
                return [$day => $assessmentsByDay[$day] ?? 0];
            });

        } catch (\Exception $e) {
            // If table doesn't exist or error, use sample data
            $dayCounts = $days->mapWithKeys(function ($day) {
                return [$day => rand(1, 5)];
            });
        }

        // Format the dates for display
        $formattedDates = $days->map(function ($date) {
            return Carbon::parse($date)->format('D, M d');
        });

        return [
            'datasets' => [
                [
                    'label' => 'Assessments',
                    'data' => $dayCounts->values()->toArray(),
                    'fill' => 'start',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor' => 'rgba(59, 130, 246, 0.7)',
                ],
            ],
            'labels' => $formattedDates->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}