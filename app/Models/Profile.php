<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Profile extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'profiles';
    protected $fillable = [
        'lastname',
        'firstname',
        'middlename',
        'extension',
        'precinct_number',
        'avatar',
        'id_type',
        'id_card_front',
        'id_card_back',
        'esig',
        'region',
        'province',
        'municipality_city',
        'barangay',
        'street',
        'gender',
        'birthdate',
        'civil_status',
        'blood_type',
        'religion',
        'tribe',
        'industry_sector',
        'occupation',
        'position',
        'income_level',
        'affiliation',
        'facebook',
        'livelihood',
        'user_id',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
