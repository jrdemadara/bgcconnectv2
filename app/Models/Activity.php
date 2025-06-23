<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'location',
        'date_start',
        'date_end',
        'points',
    ];
}
