<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupChatInvite implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat;
    public $participants;

    public function __construct(Chat $chat, array $participantIds)
    {
        $this->chat = $chat;
        $this->participants = $participantIds;
    }

    public function broadcastOn(): array
    {
        // Broadcast to all participants
        return collect($this->participants)
            ->pluck("id")
            ->map(function ($id) {
                return new PrivateChannel("user.$id");
            })
            ->all();
    }

    public function broadcastAs(): string
    {
        return "group-chat-invite";
    }

    public function broadcastWith(): array
    {
        $profile = $this->sender->profile;

        return [
            "chat_id" => $this->id,
            "chat_name" => $this->chat->name,
        ];
    }
}
