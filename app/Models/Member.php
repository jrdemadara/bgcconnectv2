<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Member extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = "users";
    protected $fillable = [
        "code",
        "phone",
        "password",
        "referred_by",
        "points",
        "level",
        "location",
        "is_active",
        "id_status",
        "fcm_token",
    ];

    protected $hidden = ["password", "remember_token"];

    protected function casts(): array
    {
        return [
            "phone_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, "user_id");
    }

    public function devices(): HasMany
    {
        return $this->hasMany(UserDevice::class, "user_id");
    }
}
