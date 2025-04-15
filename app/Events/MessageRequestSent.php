<?php

namespace App\Events;

use App\Models\Member;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class MessageRequestSent implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public Member $sender;
    public int $recipientId;

    public function __construct(Member $sender, int $recipientId)
    {
        $this->sender = $sender;
        $this->recipientId = $recipientId;
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('user.' . $this->recipientId);
    }

    public function broadcastAs(): string
    {
        return 'message-request';
    }

    public function broadcastWith(): array
    {
        $profile = $this->sender->profile;

        return [
            'sender' => [
                'id' => $this->sender->id,
                'firstname' => $profile->firstname ?? '',
                'lastname' => $profile->lastname ?? '',
                'avatar' => $profile->avatar
                    ? Storage::temporaryUrl($profile->avatar, now()->addDays(5))
                    : null,
            ],
            'status' => "pending",
            'requested_at' => now()->toIso8601String()
        ];
    }
}
