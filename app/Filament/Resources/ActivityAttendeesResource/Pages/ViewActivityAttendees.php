<?php

namespace App\Filament\Resources\ActivityAttendeesResource\Pages;

use App\Filament\Resources\ActivityAttendeesResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewActivityAttendees extends ViewRecord
{
    protected static string $resource = ActivityAttendeesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
