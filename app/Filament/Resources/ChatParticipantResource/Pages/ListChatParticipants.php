<?php

namespace App\Filament\Resources\ChatParticipantResource\Pages;

use App\Filament\Resources\ChatParticipantResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
class ListChatParticipants extends ListRecords
{
    protected static string $resource = ChatParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return [
            Tab::make('topic')
                ->label('TOPIC')
                ->query(fn($query) => $query->whereHas('chat', fn($q) => $q->where('chat_type', 'topic'))),

            Tab::make('group')
                ->label('GROUP')
                ->query(fn($query) => $query->whereHas('chat', fn($q) => $q->where('chat_type', 'group'))),

            Tab::make('direct')
                ->label('DIRECT')
                ->query(fn($query) => $query->whereHas('chat', fn($q) => $q->where('chat_type', 'direct'))),
        ];
    }
}
