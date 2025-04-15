<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Chat extends Model
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    protected $connection = 'pgsql';
    protected $fillable = [
        'chat_type',
        'name',
    ];

    public function participants(): HasMany
    {
        return $this->hasMany(ChatParticipant::class);
    }
}
