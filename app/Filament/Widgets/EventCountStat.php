<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ActivityAttendeesResource\Pages\ViewActivityAttendees;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\ActivityAttendees;

class EventCountStat extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 2;
    protected function getStats(): array
    {
        $totalAttendees = ActivityAttendees::count();

        return [
            Stat::make('Total Attendance', number_format($totalAttendees))
                ->description('All attendees recorded')
                ->color('primary'),
        ];
    }
}
