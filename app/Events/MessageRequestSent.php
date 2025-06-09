<?php

namespace App\Events;

use App\Models\Member;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class MessageRequestSent implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public int $id;
    public Member $sender;
    public int $recipientId;

    public function __construct(int $id, Member $sender, int $recipientId)
    {
        $this->id = $id;
        $this->sender = $sender;
        $this->recipientId = $recipientId;
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel("user.$this->recipientId");
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
                "avatar" => $profile->avatar ? Storage::temporaryUrl($profile->avatar, now()->addDays(5)) : null,
            ],
            "id" => $this->id,
            "status" => "pending",
            "requested_at" => now()->toIso8601String(),
        ];
    }
}
