<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ActiveUsersWidget extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Active Users', User::where('is_active', true)->count())
                ->description('Total number of active users')
                ->icon('heroicon-o-user-group')
                ->color('success'),
        ];
    }
}
