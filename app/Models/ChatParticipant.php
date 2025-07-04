<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ChatParticipant extends Model
{
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $fillable = [
        'chat_id',
        'user_id',
        'role',
        'joined_at',
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }
    public function profile()
    {
        return Profile::on('mysql')->where('user_id', $this->user_id)->first();
    }
}
