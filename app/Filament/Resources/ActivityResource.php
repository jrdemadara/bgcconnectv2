<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Activity;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Components\Grid;
use Illuminate\Database\Eloquent\Model;
use Filament\Infolists\Components\Split;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\ActivityResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ActivityResource\RelationManagers;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;
    protected static ?string $navigationGroup = 'Events';
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
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(191),
                Forms\Components\Textarea::make('location')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('date_start')
                    ->required(),
                Forms\Components\DatePicker::make('date_end')
                    ->required(),
                Forms\Components\TextInput::make('points')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([10, 25, 50, 100])
            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_start')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_end')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('points')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
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
                //  Tables\Actions\EditAction::make(),
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
                Section::make('Activity Details')
                    ->schema([
                        Fieldset::make('Basic Info')
                            ->schema([
                                TextEntry::make('code')->label('Code'),
                                TextEntry::make('name')->label('Name'),
                                TextEntry::make('description')
                                    ->label('Description')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                    ]),

                Section::make('Schedule')
                    ->schema([
                        Fieldset::make('Dates')
                            ->schema([
                                TextEntry::make('date_start')
                                    ->label('Start Date')
                                    ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('M d, Y') : '-'),

                                TextEntry::make('date_end')
                                    ->label('End Date')
                                    ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('M d, Y') : '-'),
                            ])
                            ->columns(2),
                    ]),

                Section::make('Location & Points')
                    ->schema([
                        Fieldset::make('Details')
                            ->schema([
                                TextEntry::make('location')
                                    ->label('Location')
                                    ->columnSpanFull(),

                                TextEntry::make('points')
                                    ->label('Points')
                                    ->formatStateUsing(fn($state) => $state . ' pts'),
                            ])
                            ->columns(2),
                    ]),

                Section::make('Metadata')
                    ->schema([
                        Split::make([
                            Fieldset::make(' ')
                                ->schema([
                                    TextEntry::make('created_at')
                                        ->label('Created At')
                                        ->weight(FontWeight::Medium)
                                        ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('M d, Y h:i A') : '-'),
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
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivity::route('/create'),
            'view' => Pages\ViewActivity::route('/{record}'),
            // 'edit' => Pages\EditActivity::route('/{record}/edit'),
        ];
    }
}
