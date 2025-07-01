<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\ActivityAttendees;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ActiveUsers extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $active = User::where('is_active', true)->count();
        $inactive = User::where('is_active', false)->count();
        $total = User::count();
        $totalAttendees = ActivityAttendees::count();

        $activePercent = $total > 0 ? round(($active / $total) * 100) : 0;
        $inactivePercent = $total > 0 ? round(($inactive / $total) * 100) : 0;

        return [
            Stat::make('🟢 Active Users', number_format($active))
                ->description("{$activePercent}% of all users")
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->chart([7, 9, 8, 11, 10, 14, 16]),

            Stat::make('🔴 Inactive Users', number_format($inactive))
                ->description("{$inactivePercent}% not active")
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger')
                ->chart([5, 4, 6, 3, 5, 2, 1]),

            Stat::make('👥 Total Attendance', number_format($totalAttendees))
                ->description('All event attendees recorded')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary')
                ->chart([10, 12, 15, 13, 18, 16, 20]),
        ];
    }
}
