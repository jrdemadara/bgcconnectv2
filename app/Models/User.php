<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'code',
        'phone',
        'verification_code',
        'phone_verified_at',
        'password',
        'referred_by',
        'points',
        'level',
        'is_active',
        'id_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }
       

        public function directReferrals()
        {
            return $this->hasManyThrough(
                User::class,
                Referral::class,
                'referrer_id',
                'id',
                'id',
                'referred_id'
            );
        }

        public function indirectReferrals()
        {
            return User::whereIn('id', function ($query) {
                $query->select('referred_id')
                    ->from('referrals')
                    ->whereIn('referrer_id', function ($query2) {
                        $query2->select('referred_id')
                                ->from('referrals')
                                ->where('referrer_id', $this->id);
                    });
            });
        }

       
     
}
