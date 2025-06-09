<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\DateTimeColumn;
use Illuminate\Support\Str;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\ToggleColumn;
class UserResource extends Resource
{
    public static function canCreate(): bool
{
    return false;
}
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                        ->label('Full Name')
                        
                        ->getStateUsing(fn ($record) => 
                           Str::title(trim(
                                $record->profile?->firstname . ' ' . 
                                $record->profile?->middlename . ' ' . 
                                $record->profile?->lastname
                            ))
                        ),
                TextColumn::make('code')->label('Code'),
                TextColumn::make('phone')->label('Phone'),
                TextColumn::make('referrer_full_name')
                        ->label('Referred By')
                        ->getStateUsing(function ($record) {
                            $first = $record->referrer?->profile?->firstname;
                            $last = $record->referrer?->profile?->lastname;

                            if (!$first && !$last) {
                                return null;
                            }

                            return Str::title(trim("{$first} {$last}"));
                        })
                        ->default('N/A'),
                TextColumn::make('points')->label('Points'),
                TextColumn::make('level')
                        ->label('Verification')
                        ->formatStateUsing(function ($state) {
                            return match ($state) {
                                1 => 'Registered',
                                2 => 'Profiled',
                                3 => 'OTP',
                                4 => 'Valid ID',
                                default => 'Unknown',
                            };
                        }),
               ToggleColumn::make('is_active')
                                    ->label('Active'),
               TextColumn::make('id_status')
                                ->label('Status')
                                ->badge()
                                ->color(fn ($state) => match ($state) {
                                    1 => 'info',     // Unverified
                                    2 => 'success',    // Verified
                                    3 => 'danger',      // Denied
                                    default => 'gray', // Undefined
                                })
                                ->formatStateUsing(fn ($state) => match ($state) {
                                    1 => 'Unverified',
                                    2 => 'Verified',
                                    3 => 'Denied',
                                    default => 'Undefined',
                                }),
            ])
            ->filters([
                //
            ])
            ->actions([
            
            ])
            ->bulkActions([
               
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
            'index' => Pages\ListUsers::route('/'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
