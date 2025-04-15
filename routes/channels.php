<?php


use Illuminate\Support\Facades\Broadcast;

Broadcast::routes([
    'middleware' => ['auth:sanctum'],
]);

// authorization for private channel
Broadcast::channel('user.{userId}', function (\App\Models\Member $user, $userId) {
    return (int)$user->id === (int)$userId;
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
