<?php

namespace App\Http\Controllers\Chat;

use App\Events\ReactToMessage;
use App\Http\Controllers\Controller;
use App\Models\ChatParticipant;
use App\Models\Message;
use App\Models\MessageReaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class MessageReactionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            "messageId" => "required|integer|exists:pgsql.messages,id",
            "reaction" => "required|string",
        ]);

        $userId = auth()->id();

        $reaction = MessageReaction::updateOrCreate(
            [
                "message_id" => $request->messageId,
                "user_id" => auth()->id(),
            ],
            [
                "reaction" => $request->reaction,
            ]
        );

        $chatId = Message::where("id", $request->messageId)->value("chat_id");

        // Broadcast reactions to chat channel
        broadcast(new ReactToMessage(messageReaction: $reaction, chatId: $chatId));
        // Push Notification

        return response()->json(
            [
                "message" => "success",
                "remoteId" => $reaction->id,
            ],
            Response::HTTP_CREATED
        );
    }

    public function update(Request $request): JsonResponse {}

    public function destroy(Request $request): JsonResponse {}
}
