<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Assessment;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class UserActivityWidget extends Widget
{
    protected static string $view = 'filament.widgets.user-activity-widget';
    
    // Set a lower order to make it appear after the StatsOverview
    protected static ?int $sort = 3;
    
    // Make the widget take up the full width
    protected int | string | array $columnSpan = 'full';
    
    public function getUserMetrics()
    {
        // Registration trends (last 7 days)
        $registrationTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = User::whereDate('created_at', $date)->count();
            $registrationTrend[$date] = $count;
        }
        
        // User verification status
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $unverifiedUsers = User::whereNull('email_verified_at')->count();
        
        // User assessment completion rate
        $usersWithAssessments = User::whereHas('assessments')->count();
        $usersWithoutAssessments = User::whereDoesntHave('assessments')->count();
        
        // User retention (active in last 30 days)
        $activeUsers = User::whereDate('updated_at', '>=', now()->subDays(30))->count();
        $inactiveUsers = User::whereDate('updated_at', '<', now()->subDays(30))->count();
        
        // Most active users (by assessment count)
        $mostActiveUsers = User::withCount('assessments')
            ->orderBy('assessments_count', 'desc')
            ->take(5)
            ->get();
        
        return [
            'registrationTrend' => $registrationTrend,
            'verifiedUsers' => $verifiedUsers,
            'unverifiedUsers' => $unverifiedUsers,
            'usersWithAssessments' => $usersWithAssessments,
            'usersWithoutAssessments' => $usersWithoutAssessments,
            'activeUsers' => $activeUsers,
            'inactiveUsers' => $inactiveUsers,
            'mostActiveUsers' => $mostActiveUsers,
        ];
    }
}