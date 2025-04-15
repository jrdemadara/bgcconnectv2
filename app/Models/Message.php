<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Message extends Model
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    protected $connection = 'pgsql';
    protected $fillable = [
        'chat_id',
        'sender_id',
        'content',
        'message_type',
        'reply_to',
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'reply_to');
    }

    public function messageStatus(): HasOne
    {
        return $this->hasOne(MessageStatus::class);
    }


}
