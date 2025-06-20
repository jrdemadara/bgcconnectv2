<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    protected $fillable = [
        "device_id",
        "name",
        "last_login",
        "login_lat",
        "login_lon",
        "is_online",
        "user_id",
    ];
}
