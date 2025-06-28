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
    protected $connection = 'mysql';
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
    public function getBrgyDescriptionAttribute()
    {
        return Barangay::where('brgyCode', $this->barangay)->value('brgyDescription');
    }
    public function getCityDescriptionAttribute()
    {
        return Municipality::where('citymunCode', $this->municipality_city)->value('citymunDescription');
    }
    public function getProvDescriptionAttribute()
    {
        return Province::where('provCode', $this->province)->value('provDescription');
    }
    public function getRegDescriptionAttribute()
    {
        return Region::where('regCode', $this->region)->value('regDescription');
    }

    public function profile()
    {
        return $this->belongsTo(\App\Models\Profile::class, 'user_id', 'user_id')
            ->setConnection('mysql'); // <-- set correct connection name
    }
    public function getFullNameAttribute()
    {
        return trim("{$this->lastname} {$this->firstname} {$this->middlename}");
    }
}
