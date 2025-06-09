<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    protected $fillable = [
        'lastname',
        'firstname',
        'middlename',
        'extension',
        'precint_number',
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
        'user_id',
    ];
}
