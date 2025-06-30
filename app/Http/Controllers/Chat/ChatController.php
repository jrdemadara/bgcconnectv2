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
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Support\Str;
use App\Models\Member;

class ChatController extends Controller
{
    public function sendChat(Request $request): JsonResponse
    {
        $request->validate([
            "localId" => "required|integer",
            "chatId" => "required|integer|exists:pgsql.chats,id",
            "content" => "required|string|max:5000",
            "messageType" => "required|in:text,image,video,file",
            "replyTo" => "nullable|integer|exists:pgsql.messages,id",
        ]);

        $senderId = auth()->id();
        $chatId = $request->chatId;

        // Check if sender is part of the chat
        $isParticipant = ChatParticipant::where("chat_id", $chatId)
            ->where("user_id", $senderId)
            ->exists();

        if (!$isParticipant) {
            return response()->json(
                ["message" => "You are not part of this chat"],
                Response::HTTP_FORBIDDEN
            );
        }

        // Create message
        $message = Message::create([
            "sender_id" => $senderId,
            "chat_id" => $chatId,
            "content" => $request->content,
            "message_type" => $request->messageType,
            "reply_to" => $request->replyTo,
        ]);

        $participantIds = ChatParticipant::where("chat_id", $chatId)->pluck("user_id");

        // Store status for each participant
        foreach ($participantIds as $userId) {
            $status = $userId == $senderId ? "sent" : "delivered";

            $insertedId = DB::connection("pgsql")
                ->table("message_status")
                ->insertGetId([
                    "message_id" => $message->id,
                    "user_id" => $userId,
                    "status" => $status,
                ]);

            $statusPayload = [
                "id" => $insertedId,
                "message_id" => $message->id,
                "user_id" => $userId,
                "status" => $status,
                "updated_at" => $message->updated_at->toIso8601String(),
            ];

            // Send FCM if not the sender
            if ($userId != $senderId) {
                broadcast(new MessageSent($message, $statusPayload, $request->localId));

                $fcmToken = Member::where("id", $userId)->value("fcm_token");

                if ($fcmToken) {
                    $messaging = app("firebase.messaging");

                    $firebaseMessage = CloudMessage::new()
                        ->withData([
                            "type" => "chat-received",
                            "title" => "New Message",
                            "body" => Str::limit($message->content, 120, "..."),
                            "message" => json_encode([
                                "id" => $message->id,
                                "sender_id" => $message->sender_id,
                                "chat_id" => $message->chat_id,
                                "content" => $message->content,
                                "message_type" => $message->message_type,
                                "reply_to" => $message->reply_to ?? null,
                                "created_at" => $message->created_at->toIso8601String(),
                                "updated_at" => $message->updated_at->toIso8601String(),
                            ]),
                            "status" => json_encode($statusPayload),
                            "local_id" => $request->localId,
                        ])
                        ->toToken($fcmToken);

                    $messaging->send($firebaseMessage);
                }
            }
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
            broadcast(
                new MessageRead($request->chat_id, $senderId, $user->id, [$request->message_id])
            )->toOthers();
        }

        return response()->json(["status" => "ok"]);
    }
}
