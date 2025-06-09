<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageRead implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $chatId;
    public int $senderId;
    public int $readerId;
    public array $messageIds;

    public function __construct(int $chatId, int $senderId, int $readerId, array $messageIds)
    {
        $this->chatId = $chatId;
        $this->senderId = $senderId;
        $this->readerId = $readerId;
        $this->messageIds = $messageIds;
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel("chat.{$this->chatId}");
    }

    public function broadcastAs(): string
    {
        return "chat-read";
    }

    public function broadcastWith(): array
    {
        return [
            "reader_id" => $this->readerId,
            "message_ids" => $this->messageIds,
        ];
    }
}
