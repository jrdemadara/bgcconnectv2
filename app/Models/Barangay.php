<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    protected $connection = 'mysql_address';
    protected $table = 'address_barangay'; // Table name, adjust if different

    protected $fillable = [
        'brgyCode',
        'brgyDescription',
        'citymunCode',
        'brgyRemarks',
    ];

   
}