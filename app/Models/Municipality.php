<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
   
    protected $connection = 'mysql_address';
    protected $table = 'address_citymun'; // Table name, adjust if different

    protected $fillable = [
        'citymunCode',
        'citymunDescription',
        'provCode',
        'citymunRemarks',
        
    ];
}
