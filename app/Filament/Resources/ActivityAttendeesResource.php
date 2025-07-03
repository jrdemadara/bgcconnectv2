<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Models\ActivityAttendees;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\DateEntry;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ActivityAttendeesResource\Pages;
use App\Filament\Resources\ActivityAttendeesResource\RelationManagers;

use Filament\Infolists\Components\Fieldset;


class ActivityAttendeesResource extends Resource
{

    protected static ?string $model = ActivityAttendees::class;
    public static function canCreate(): bool
    {
        return false;
    }

    //  protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Events';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('activity_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([10, 25, 50, 100])
            ->columns([
                TextColumn::make('profile') // just a dummy placeholder
                    ->label('Full Name')
                    ->searchable(query: function ($query, $search) {
                        $query->orWhereHas('profile', function ($q) use ($search) {
                            $q->where('lastname', 'like', "%{$search}%")
                                ->orWhere('firstname', 'like', "%{$search}%")
                                ->orWhere('middlename', 'like', "%{$search}%");
                        });
                    })
                    ->formatStateUsing(function ($state, $record) {
                        $profile = $record->profile;
                        if (!$profile)
                            return null;
                        return Str::title(trim("{$profile->lastname}, {$profile->firstname} {$profile->middlename}"));
                    }),
                Tables\Columns\TextColumn::make('activity.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
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
                Section::make('Activity Attendee Info')
                    ->schema([
                        Fieldset::make('Attendee Details')
                            ->schema([
                                TextEntry::make('activity.name')
                                    ->label('Activity Name'),

                                TextEntry::make('profile.full_name')
                                    ->label('Full Name')
                                    ->formatStateUsing(fn($state) => Str::title($state)),

                                TextEntry::make('created_at')
                                    ->label('Created At')
                                    ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('M d, Y h:i A') : '-'),

                                TextEntry::make('updated_at')
                                    ->label('Updated At')
                                    ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('M d, Y h:i A') : '-'),
                            ])
                            ->columns(2),
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
            'index' => Pages\ListActivityAttendees::route('/'),
            //'create' => Pages\CreateActivityAttendees::route('/create'),
            //'view' => Pages\ViewActivityAttendees::route('/{record}'),
            // 'edit' => Pages\EditActivityAttendees::route('/{record}/edit'),
        ];
    }
}
