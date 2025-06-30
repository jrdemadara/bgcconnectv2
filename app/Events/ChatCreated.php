<?php

namespace App\Events;

use App\Models\Chat;
use App\Models\Member;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ChatCreated implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $chat;
    public $participants;
    public $messageRequestId;

    public function __construct(Chat $chat, int $messageRequestId, array $participantIds)
    {
        $this->chat = $chat;
        $this->messageRequestId = $messageRequestId;
        $this->participants = Member::with("profile")
            ->whereIn("id", $participantIds)
            ->get()
            ->map(function ($member) {
                return [
                    "id" => $member->id,
                    "firstname" => $member->profile->firstname ?? "",
                    "lastname" => $member->profile->lastname ?? "",
                    "avatar" => $member->profile->avatar
                        ? Storage::temporaryUrl($member->profile->avatar, now()->addDays(5))
                        : null,
                ];
            })
            ->values();
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

    public function broadcastAs()
    {
        return "chat-created";
    }

    public function broadcastWith(): array
    {
        return [
            "chat" => [
                "id" => $this->chat->id,
                "chat_type" => $this->chat->chat_type,
                "name" => $this->chat->name,
            ],
            "participants" => $this->participants,
            "message_request_id" => $this->messageRequestId,
        ];
    }
}
