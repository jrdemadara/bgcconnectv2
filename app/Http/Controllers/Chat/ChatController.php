<?php

namespace App\Http\Controllers\Chat;

use App\Events\MessageRead;
use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\ChatParticipant;
use App\Models\Message;
use App\Models\MessageStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ChatController extends Controller
{
    public function sendChat(Request $request): JsonResponse
    {
        $request->validate([
            "localId" => "required|integer",
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

        $participantIds = ChatParticipant::where("chat_id", $chatId)->pluck("user_id");

        $senderStatus = null;
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

            if ($userId != $senderId) {
                $receiverStatus = [
                    "id" => $insertedId,
                    "message_id" => $message->id,
                    "user_id" => $userId,
                    "status" => $status,
                    "updated_at" => now()->toIso8601String(),
                ];
            } else {
                $senderStatus = [
                    "id" => $insertedId,
                    "message_id" => $message->id,
                    "user_id" => $userId,
                    "status" => $status,
                    "updated_at" => now()->toIso8601String(),
                ];
            }
        }

        if ($receiverStatus) {
            broadcast(new MessageSent($message, $receiverStatus, $request->localId));
        }

        return response()->json(
            [
                "message" => "success",
            ],
            Response::HTTP_CREATED
        );
    }

    public function messageRead(Request $request)
    {
        $request->validate([
            "chat_id" => "required|integer|exists:pgsql.chats,id",
            "message_id" => "required|integer|exists:pgsql.messages,id",
        ]);

        $user = auth()->user();

        MessageStatus::where("message_id", $request->message_id)
            ->where("user_id", $user->id)
            ->update(["status" => "read"]);

        $senderId = Message::where("id", $request->message_id)->value("sender_id");

        // Broadcast to sender
        if ($senderId && $senderId != $user->id) {
            broadcast(new MessageRead($request->chat_id, $senderId, $user->id, [$request->message_id]))->toOthers();
        }

        return response()->json(["status" => "ok"]);
    }
}
