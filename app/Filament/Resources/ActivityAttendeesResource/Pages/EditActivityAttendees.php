<?php

namespace App\Filament\Resources\ActivityAttendeesResource\Pages;

use App\Filament\Resources\ActivityAttendeesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditActivityAttendees extends EditRecord
{
    protected static string $resource = ActivityAttendeesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
