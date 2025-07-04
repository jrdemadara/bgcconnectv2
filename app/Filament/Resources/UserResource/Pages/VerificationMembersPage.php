<?php

namespace App\Filament\Resources\UserResource\Pages;

use DateTime;
use Illuminate\Support\Str;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Carbon\Carbon;

class VerificationMembersPage extends EditRecord
{
    protected static string $resource = UserResource::class;
    public function getTitle(): string
    {
        return 'Verify User';
    }
    public function form(Form $form): Form
    {
        $profile = $this->record->profile;

        $fullName = $profile
            ? Str::title(trim("{$profile->lastname}, {$profile->firstname} {$profile->middlename}"))
            : 'User';

        $birthdate = optional($profile)->birthdate;
        $gender = optional($profile)->gender;

        // Calculate age using Carbon
        $age = $birthdate ? Carbon::parse($birthdate)->age : null;

        return $form
            ->schema([
                Section::make('Member Verification')
                    ->schema([
                        View::make('forms.components.verification')
                            ->viewData([
                                'name' => $fullName,
                                'birthdate' => $birthdate,
                                'age' => $age,
                                'gender' => $gender,
                            ]),

                        Select::make('status')
                            ->label('ID Status')
                            ->options([
                                1 => 'Unverified',
                                2 => 'Verified',
                                3 => 'Denied',
                            ])
                            ->required(),
                    ])
                    ->columns(1),
            ]);
    }
}
