<?php

namespace App\Filament\Resources;


use App\Models\User;
use Filament\Infolists\Components\View;

use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Infolists\Components\overview;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Tabs;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\UserResource\Pages;

class UserResource extends Resource
{

    public static function canCreate(): bool
    {
        return false;
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    protected static ?string $navigationBadgeTooltip = 'The number of BGC users';
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Members';
    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            ->paginated([10, 25, 50, 100])
            ->recordUrl(null)
            ->columns([
                TextColumn::make('id')->label('User Id')
                    ->searchable(),
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
                    ->sortable()
                    ->color(fn($state) => match ($state) {
                        1 => 'info',     // Unverified
                        2 => 'success',    // Verified
                        3 => 'danger',      // Denied
                        default => 'gray', // Undefined
                    })
                    ->formatStateUsing(fn($state) => match ($state) {
                        1 => 'Unverified',
                        2 => 'Verified',
                        3 => 'Denied',
                        default => 'Undefined',
                    }),
            ])


            ->filters([
                SelectFilter::make('level')
                    ->label('Verification Level')
                    ->options([
                        1 => 'Registered',
                        2 => 'Profiled',
                        3 => 'OTP',
                        4 => 'Valid ID',
                    ]),

                SelectFilter::make('id_status')
                    ->label('ID Status')
                    ->options([
                        1 => 'Unverified',
                        2 => 'Verified',
                        3 => 'Denied',
                    ]),

            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([]);
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make()
                    ->columns(1)
                    ->schema([

                        Tabs::make('User Info Tabs')

                            ->columnSpanFull()
                            ->tabs([
                                Tabs\Tab::make('Overview')
                                    ->icon('heroicon-m-user-circle')
                                    ->schema([
                                        Section::make(' ') // ← outer section
                                            ->schema([
                                                Section::make(' ')           // ← inner section
                                                    ->schema([
                                                        View::make('infolists.components.overview')
                                                            ->viewData(fn($record) => [
                                                                'record' => $record,
                                                            ]),
                                                    ]),
                                            ]),
                                    ]),


                                Tabs\Tab::make('Profile')
                                    ->icon('heroicon-m-identification')
                                    ->iconPosition(IconPosition::After)
                                    ->schema([
                                        Section::make('Account Information')
                                            ->columns(2)
                                            ->schema([
                                                TextEntry::make('full_name')
                                                    ->label('Full Name')
                                                    ->getStateUsing(
                                                        fn($record) =>
                                                        Str::title(trim(
                                                            $record->profile?->firstname . ' ' .
                                                            $record->profile?->middlename . ' ' .
                                                            $record->profile?->lastname . ' ' .
                                                            $record->profile?->extension
                                                        ))
                                                    ),
                                                TextEntry::make('code')->label('Code'),
                                                TextEntry::make('phone')->label('Phone'),
                                                TextEntry::make('referrer_full_name')
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
                                                TextEntry::make('points')->label('Points'),
                                                TextEntry::make('level')
                                                    ->label('Verification')
                                                    ->formatStateUsing(fn($state) => match ($state) {
                                                        1 => 'Registered',
                                                        2 => 'Profiled',
                                                        3 => 'OTP',
                                                        4 => 'Valid ID',
                                                        default => 'Unknown',
                                                    }),
                                                TextEntry::make('id_status')
                                                    ->label('Status')
                                                    ->badge()
                                                    ->color(fn($state) => match ($state) {
                                                        1 => 'info',
                                                        2 => 'success',
                                                        3 => 'danger',
                                                        default => 'gray',
                                                    })
                                                    ->formatStateUsing(fn($state) => match ($state) {
                                                        1 => 'Unverified',
                                                        2 => 'Verified',
                                                        3 => 'Denied',
                                                        default => 'Undefined',
                                                    }),
                                                TextEntry::make('is_active')
                                                    ->label('Active')
                                                    ->formatStateUsing(fn($state) => $state ? 'Active' : 'Inactive')
                                                    ->color(fn($state) => $state ? 'success' : 'danger'),
                                            ]),

                                        Section::make('Personal Details')
                                            ->columns(2)
                                            ->schema([
                                                TextEntry::make('profile.firstname')->label('First Name'),
                                                TextEntry::make('profile.middlename')->label('Middle Name'),
                                                TextEntry::make('profile.lastname')->label('Last Name'),
                                                TextEntry::make('profile.extension')
                                                    ->label('Extension')
                                                    ->default('N/A'),
                                                TextEntry::make('profile.gender')->label('Gender'),
                                                TextEntry::make('profile.birthdate')->label('Birthdate'),
                                                TextEntry::make('profile.civil_status')->label('Civil Status'),
                                                TextEntry::make('profile.blood_type')->label('Blood Type'),
                                                TextEntry::make('profile.religion')->label('Religion'),
                                                TextEntry::make('profile.tribe')->label('Tribe'),
                                            ]),

                                        Section::make('Address Information')
                                            ->columns(2)
                                            ->schema([
                                                TextEntry::make('profile.provDescription')->label('Province'),
                                                TextEntry::make('profile.cityDescription')->label('Municipality / City'),
                                                TextEntry::make('profile.brgyDescription')->label('Barangay'),
                                                TextEntry::make('profile.street')->label('Street'),
                                                TextEntry::make('profile.precinct_number')->label('Precinct Number'),
                                            ]),

                                        Section::make('Employment Information')
                                            ->columns(2)
                                            ->schema([
                                                TextEntry::make('profile.industry_sector')->label('Industry Sector'),
                                                TextEntry::make('profile.occupation')->label('Occupation'),
                                                TextEntry::make('profile.position')->label('Position'),
                                                TextEntry::make('profile.income_level')->label('Income Level'),
                                                TextEntry::make('profile.livelihood')->label('Livelihood'),
                                                TextEntry::make('profile.affiliation')->label('Affiliation'),
                                            ]),

                                        Section::make('Other Details')
                                            ->columns(2)
                                            ->schema([
                                                TextEntry::make('profile.id_type')->label('ID Type'),
                                                TextEntry::make('profile.facebook')->label('Facebook'),
                                            ]),
                                    ]),

                                Tabs\Tab::make('Refererals')
                                    ->icon('heroicon-m-user-group')
                                    ->iconPosition(IconPosition::After)
                                    ->schema([

                                        Tabs::make('Referral Tabs')
                                            ->tabs([
                                                Tabs\Tab::make('Direct Referrals')
                                                    ->schema([
                                                        Section::make()
                                                            ->schema([
                                                                View::make('infolists.components.direct-referrals-table')
                                                                    ->viewData(fn($record) => [
                                                                        'userId' => $record->id, // Pass user ID to Livewire
                                                                    ]),
                                                            ]),
                                                    ]),

                                                Tabs\Tab::make('Indirect Referrals')
                                                    ->schema([
                                                        Section::make()
                                                            ->schema([
                                                                View::make('infolists.components.indirect-referrals-table')
                                                                    ->viewData(fn($record) => [
                                                                        'userId' => $record->id,
                                                                    ]),
                                                            ]),
                                                    ])
                                            ])
                                    ]),


                            ])
                            ->contained(false)
                    ])
                    ->columnSpanFull(),
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
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}
