<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class MessageRequest extends Model
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    protected $connection = 'pgsql';
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'status',
    ];
}
