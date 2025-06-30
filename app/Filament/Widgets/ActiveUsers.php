<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ActiveUsers extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        $active = User::where('is_active', true)->count();
        $inactive = User::where('is_active', false)->count();
        $total = $active + $inactive;

        return [
            Stat::make('🟢 Active Users', number_format($active))
                ->description("Out of " . number_format($total) . " total users")
                ->color('success'),

            Stat::make('🔴 Inactive Users', number_format($inactive))
                ->description("Marked as not active")
                ->color('danger'),

            Stat::make('👥 Total Users', number_format($total))
                ->description('Registered in the system')
                ->color('primary'),
        ];
    }
}
