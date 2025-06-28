<?php

namespace App\Filament\Resources\ActivityAttendeesResource\Pages;

use App\Filament\Resources\ActivityAttendeesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActivityAttendees extends ListRecords
{
    protected static string $resource = ActivityAttendeesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
