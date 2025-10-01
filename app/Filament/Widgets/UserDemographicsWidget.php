<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Assessment;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class UserDemographicsWidget extends ChartWidget
{
    protected static ?string $heading = 'User Registration Trends';
    
    protected static ?int $sort = 4;
    
    protected function getData(): array
    {
        // Get registration data by month for the past 6 months
        $monthLabels = [];
        $monthData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = User::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
            
            $monthLabels[] = $month->format('M Y');
            $monthData[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'User Registrations',
                    'data' => $monthData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.8)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'tension' => 0.1,
                ],
            ],
            'labels' => $monthLabels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}