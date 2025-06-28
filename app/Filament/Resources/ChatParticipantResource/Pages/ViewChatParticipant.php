<?php

namespace App\Filament\Resources\ChatParticipantResource\Pages;

use App\Filament\Resources\ChatParticipantResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewChatParticipant extends ViewRecord
{
    protected static string $resource = ChatParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
