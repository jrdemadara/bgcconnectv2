<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class MessageStatus extends Model
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    protected $connection = "pgsql";
    protected $table = "message_status";
    protected $fillable = ["message_id", "user_id", "status"];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, "user_id");
    }
}
