<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Message extends Model
{
    use SoftDeletes;

    protected $connection = "pgsql";
    protected $fillable = ["sender_id", "chat_id", "content", "message_type", "reply_to", "edited_at"];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(Message::class, "reply_to");
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(MessageStatus::class);
    }
}
