<?php

use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Chat\SearchMemberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Chat\MessageRequestController;

Route::get("/user", function (Request $request) {
    return $request->user();
})->middleware("auth:sanctum");

Route::post("/login", [AuthController::class, "login"]);

Route::middleware("auth:sanctum")->group(function () {
    Route::post("/logut", [AuthController::class, "logout"]);
    Route::get("/profile", [AuthController::class, "profile"]);

    Route::get("/search-member", [SearchMemberController::class, "search"]);

    Route::post("/message-request", [MessageRequestController::class, "messageRequest"]);
    Route::patch("/message-request-accept", [MessageRequestController::class, "acceptMessageRequest"]);
    Route::patch("/message-request-decline", [MessageRequestController::class, "declineMessageRequest"]);

    Route::post("/chat-send", [ChatController::class, "sendChat"]);
});
