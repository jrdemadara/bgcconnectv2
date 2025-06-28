<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityAttendees extends Model
{
    protected $fillable = [
        'activity_id',
        'user_id',
    ];

    public $timestamps = true;

    // Optional: relationships
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
      public function profile()
    {
        return $this->hasOne(Profile::class, "user_id", "id");
    }
}
