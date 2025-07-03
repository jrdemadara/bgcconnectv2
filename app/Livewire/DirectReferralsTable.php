<?php
namespace App\Livewire;

use App\Models\User;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Tables\TableComponent;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class DirectReferralsTable extends TableComponent
{
    public ?int $userId = null; // ID of the referrer

    public function table(Table $table): Table
    {
        return $table
            ->paginated([10, 25, 50, 100])
            ->query(fn() => User::query()
                ->with('profile') // eager load the profile
                ->where('referred_by', $this->userId)) // filter direct referrals
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
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
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
            ]);
    }

    public function render()
    {
        return view('livewire.direct-referrals-table');
    }
}
