<?php

namespace App\Filament\Resources;

use Log;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ChatParticipant;
use Filament\Resources\Resource;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ChatParticipantResource\Pages;
use App\Filament\Resources\ChatParticipantResource\RelationManagers;

class ChatParticipantResource extends Resource
{
    protected static ?string $model = ChatParticipant::class;
    protected static ?string $navigationGroup = 'BGC CHAT';
    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('chat_id')
                    ->relationship('chat', 'name')
                    ->required(),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('role')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('joined_at')
                    ->required(),
                Forms\Components\Toggle::make('is_joined')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('chat.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id'),

                Tables\Columns\TextColumn::make('role')
                    ->searchable(),
                Tables\Columns\TextColumn::make('joined_at')
                    ->dateTime()
                    ->sortable(),
                ToggleColumn::make('is_joined')
                    ->label('Joined')
                    ->onColor('success') // Green when ON
                    ->offColor('danger') // Red when OFF
                    ->sortable()
                    ->visible(fn ($record) => $record && $record->chat && $record->chat->chat_type === 'topic'),
                Tables\Columns\TextColumn::make('chat.chat_type')
                    ->label('Chat Type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChatParticipants::route('/'),
            'create' => Pages\CreateChatParticipant::route('/create'),
            'view' => Pages\ViewChatParticipant::route('/{record}'),
            'edit' => Pages\EditChatParticipant::route('/{record}/edit'),
        ];
    }
}
