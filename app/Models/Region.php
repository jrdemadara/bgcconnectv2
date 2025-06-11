<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
 
    protected $connection = 'mysql_address';
    protected $table = 'address_region'; // Table name, adjust if different

    protected $fillable = [
        'regCode',
        'regDescription',
        'regRemarks',
        
        
    ];
}
