<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $table = "referrals";
    protected $fillable = [
        'referrer_id',
        'referred_id',
        'status',
        'created_at',
        'updated_at',
    ];
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referred()
    {
        return $this->belongsTo(User::class, 'referred_id');
    }
}

