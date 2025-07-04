<?php

namespace App\Events;

use App\Models\MessageReaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReactToMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageReaction;
    public $chatId;

    public function __construct(MessageReaction $messageReaction, int $chatId)
    {
        $this->messageReaction = $messageReaction;
        $this->chatId = $chatId;
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel("chat.{$this->chatId}")];
    }

    public function broadcastAs(): string
    {
        return "message-reaction";
    }

    public function broadcastWith(): array
    {
        return [
            "id" => $this->messageReaction->id,
            "messageId" => $this->messageReaction->message_id,
            "userId" => $this->messageReaction->user_id,
            "reaction" => $this->messageReaction->reaction,
        ];
    }
}
