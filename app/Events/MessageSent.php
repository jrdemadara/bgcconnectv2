<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class MessageSent implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $localId;
    public $message;
    public $status;

    public function __construct(Message $message, array $status, int $localId)
    {
        $this->message = $message;
        $this->status = $status;
        $this->localId = $localId;
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel("chat.{$this->message->chat_id}")];
    }

    public function broadcastAs(): string
    {
        return "chat-received";
    }

    public function broadcastWith(): array
    {
        return [
            "message" => [
                "id" => $this->message->id,
                "sender_id" => $this->message->sender_id,
                "chat_id" => $this->message->chat_id,
                "content" => $this->message->content,
                "message_type" => $this->message->message_type,
                "reply_to" => $this->message->reply_to ?? null,
                "created_at" => $this->message->created_at->toIso8601String(),
                "updated_at" => $this->message->updated_at->toIso8601String(),
            ],
            "status" => $this->status,
            "local_id" => $this->localId,
        ];
    }
}
