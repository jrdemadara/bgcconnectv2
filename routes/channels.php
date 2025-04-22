<?php

use App\Models\ChatParticipant;
use App\Models\Member;
use Illuminate\Support\Facades\Broadcast;

Broadcast::routes([
    "middleware" => ["auth:sanctum"],
]);

Broadcast::channel("user.{userId}", function (Member $user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel("chat.{chatId}", function (Member $user, $chatId) {
    // Check if the user is a participant of the chat
    return ChatParticipant::where("chat_id", $chatId)->where("user_id", $user->id)->exists();
});

Broadcast::channel("chat-presence.{chatId}", function (Member $user, $chatId) {
    return [
        "id" => $user->id,
    ];
});

// authorization for presence channel
//Broadcast::channel('user.{userId}', function ($user, $userId) {
//    if ($user->id === $userId) {
//        return array('name' => $user->name);
//    }
//});

//Broadcast::channel('private-user.{id}', function (Member $user, $id) {
//    return (int)$member->id === (int)$id;
//});
