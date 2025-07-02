<?php

namespace App\Filament\Resources;

use Log;
use Filament\Forms;
use Filament\Tables;
use App\Models\Profile;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\ChatParticipant;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Infolists\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ChatParticipantResource\Pages;
use App\Filament\Resources\ChatParticipantResource\RelationManagers;

class ChatParticipantResource extends Resource
{
    protected static ?string $model = ChatParticipant::class;
    protected static ?string $navigationGroup = 'Chat';
    protected static ?int $navigationSort = 3;
    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function canCreate(): bool
    {
        return false;
    }
    public static function canEdit(Model $record): bool
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
                TextColumn::make('user_full_name')
                    ->label('Full Name')
                    ->getStateUsing(function ($record) {
                        $profile = Profile::on('mysql')->where('user_id', $record->user_id)->first();

                        if (!$profile) {
                            return null;
                        }

                        return Str::title(trim("{$profile->lastname}, {$profile->firstname} {$profile->middlename}"));
                    })
                    ->searchable(false), // disable default search, optional

                Tables\Columns\TextColumn::make('role'),


                ToggleColumn::make('is_joined')
                    ->label('Joined')
                    ->onColor('success') // Green when ON
                    ->offColor('danger') // Red when OFF
                    ->sortable()
                    ->visible(fn($record) => $record && $record->chat && $record->chat->chat_type === 'topic'),
                Tables\Columns\TextColumn::make('chat.chat_type')
                    ->label('Chat Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('joined_at')
                    ->dateTime()
                    ->sortable(),
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
                //    Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Chat Participant Info')
                    ->schema([
                        Fieldset::make('Participant Details')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextEntry::make('chat.name')
                                        ->label('Chat Name'),

                                    TextEntry::make('user_full_name')
                                        ->label('Full Name')
                                        ->getStateUsing(function ($record) {
                                            $profile = \App\Models\Profile::on('mysql')->where('user_id', $record->user_id)->first();

                                            if (!$profile) {
                                                return null;
                                            }

                                            return Str::title(trim("{$profile->lastname}, {$profile->firstname} {$profile->middlename}"));
                                        }),

                                    TextEntry::make('role')
                                        ->label('Role'),

                                    TextEntry::make('is_joined')
                                        ->label('Joined')
                                        ->formatStateUsing(fn($state) => $state ? 'Yes' : 'No')
                                        ->visible(fn($record) => $record->chat && $record->chat->chat_type === 'topic'),

                                    TextEntry::make('chat.chat_type')
                                        ->label('Chat Type'),
                                ]),
                            ]),
                    ]),

                Section::make('Timestamps')
                    ->schema([
                        Fieldset::make('Record Dates')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextEntry::make('joined_at')
                                        ->label('Joined At')
                                        ->dateTime(),

                                    TextEntry::make('created_at')
                                        ->label('Created At')
                                        ->dateTime(),

                                    TextEntry::make('updated_at')
                                        ->label('Updated At')
                                        ->dateTime(),

                                    TextEntry::make('deleted_at')
                                        ->label('Deleted At')
                                        ->dateTime(),
                                ]),
                            ]),
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
          //  'view' => Pages\ViewChatParticipant::route('/{record}'),
            'edit' => Pages\EditChatParticipant::route('/{record}/edit'),
        ];
    }
}
