<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
  
    protected $connection = 'mysql_address';
    protected $table = 'address_province'; // Table name, adjust if different

    protected $fillable = [
        'provCode',
        'provDescription',
        'regCode',
        'provRemarks',
        
        
    ];
}
