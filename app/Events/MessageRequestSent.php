<?php

namespace App\Events;

use App\Models\Member;
use App\Models\MessageRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class MessageRequestSent implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public Member $sender;
    public MessageRequest $messageRequest;

    public function __construct(Member $sender, MessageRequest $messageRequest)
    {
        $this->sender = $sender;
        $this->messageRequest = $messageRequest;
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel("user.{$this->messageRequest->recipient_id}");
    }

    public function broadcastAs(): string
    {
        return "message-request";
    }

    public function broadcastWith(): array
    {
        $profile = $this->sender->profile;

        return [
            "sender" => [
                "id" => $this->sender->id,
                "firstname" => $profile->firstname ?? "",
                "lastname" => $profile->lastname ?? "",
                "avatar" => $profile->avatar
                    ? Storage::temporaryUrl($profile->avatar, now()->addDays(5))
                    : null,
            ],
            "id" => $this->messageRequest->id,
            "status" => "pending",
            "requested_at" => $this->messageRequest->created_at->toIso8601String(),
        ];
    }
}
