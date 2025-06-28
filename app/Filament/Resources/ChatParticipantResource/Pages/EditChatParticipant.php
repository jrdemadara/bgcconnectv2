<?php

namespace App\Filament\Resources\ChatParticipantResource\Pages;

use App\Filament\Resources\ChatParticipantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChatParticipant extends EditRecord
{
    protected static string $resource = ChatParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
