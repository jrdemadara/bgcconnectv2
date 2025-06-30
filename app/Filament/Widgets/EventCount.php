<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\ActivityAttendee;
use App\Models\ActivityAttendees;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EventCount extends ChartWidget
{
    protected static ?string $heading = 'Activity Attendees Per Day';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        // Get the last 30 days dates
        $period = collect();
        for ($i = 29; $i >= 0; $i--) {
            $period->push(Carbon::today()->subDays($i)->format('Y-m-d'));
        }

        // Query count of attendees grouped by date for last 30 days
        $data = ActivityAttendees::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', Carbon::today()->subDays(29))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date'); // Key by date for quick lookup

        // Map the data counts to the full period, fill 0 if missing
        $counts = $period->map(fn($date) => $data->get($date)->count ?? 0)->toArray();

        // Format labels (e.g. 'Jun 23')
        $labels = $period->map(fn($date) => Carbon::parse($date)->format('M d'))->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Attendees',
                    'data' => $counts,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)', // Tailwind blue-500 opacity 50%
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'fill' => true,
                    'tension' => 0.4, // smooth curve
                ],
            ],
            'labels' => $labels,
        ];
    }
}
