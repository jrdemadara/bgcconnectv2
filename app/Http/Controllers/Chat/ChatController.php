<?php

namespace App\Http\Controllers\Chat;

use App\Events\ChatMessageSent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatParticipant;
use App\Models\Message;
use App\Models\MessageRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ChatController extends Controller
{
    public function sendChat(Request $request): JsonResponse
    {
        $request->validate([
            "chatId" => "required|integer|exists:pgsql.chats,id",
            "content" => "required|string|max:5000",
            "messageType" => "required|in:text,image,video,file",
            "replyTo" => "nullable|integer",
            // "replyTo" => "nullable|integer|exists:pgsql.messages,id",
        ]);

        $senderId = auth()->id();
        $chatId = $request->chatId;

        // will be deprecated depende sa performace: verify user is a participant of the chat
        $isParticipant = ChatParticipant::where("chat_id", $chatId)->where("user_id", $senderId)->exists();
        if (!$isParticipant) {
            return response()->json(["message" => "You are not part of this chat"], Response::HTTP_FORBIDDEN);
        }

        // TODO: Process the content. If it is not text then save it to S3

        $message = Message::create([
            "sender_id" => $senderId,
            "chat_id" => $chatId,
            "content" => $request->content,
            "message_type" => $request->messageType,
            // "reply_to" => $request->replyTo,
        ]);

        // Optionally update message_status for all participants
        $participantIds = ChatParticipant::where("chat_id", $chatId)->pluck("user_id");

        $receiverStatus = null;

        foreach ($participantIds as $userId) {
            $status = $userId == $senderId ? "sent" : "delivered";

            $insertedId = DB::connection("pgsql")
                ->table("message_status")
                ->insertGetId([
                    "message_id" => $message->id,
                    "user_id" => $userId,
                    "status" => $status,
                ]);

            // Save only the receiver's status to broadcast
            if ($userId != $senderId) {
                $receiverStatus = [
                    "id" => $insertedId,
                    "message_id" => $message->id,
                    "user_id" => $userId,
                    "status" => $status,
                    "updated_at" => now()->toIso8601String(),
                ];
            }
        }

        // Broadcast message and only receiver's status
        if ($receiverStatus) {
            broadcast(new ChatMessageSent($message, $receiverStatus));
        }

        return response()->json(
            [
                "message" => "Message sent successfully",
            ],
            Response::HTTP_CREATED
        );
    }
}
