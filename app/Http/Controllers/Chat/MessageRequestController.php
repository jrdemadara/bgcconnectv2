<?php

namespace App\Http\Controllers\Chat;

use App\Events\ChatCreated;
use App\Events\ChatRequestSent;
use App\Events\MessageRequestAccepted;
use App\Events\MessageRequestSent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatParticipant;
use App\Models\Member;
use App\Models\MessageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Messaging\Notification;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Support\Str;

class MessageRequestController extends Controller
{
    public function messageRequest(Request $request): JsonResponse
    {
        $request->validate([
            "recipientId" => "required|integer|exists:users,id",
        ]);

        $sender = Member::with("profile")->find(auth()->id());
        $recipientId = $request->recipientId;
        $recipient = Member::find($recipientId);

        $messageRequestExists = MessageRequest::where(function ($query) use (
            $sender,
            $recipientId
        ) {
            $query
                ->where("sender_id", $sender->id)
                ->where("recipient_id", $recipientId)
                ->where("status", "!=", "declined");
        })
            ->orWhere(function ($query) use ($sender, $recipientId) {
                $query
                    ->where("sender_id", $recipientId)
                    ->where("recipient_id", $sender->id)
                    ->where("status", "!=", "declined");
            })
            ->exists();

        $messageRequestSelf = $sender->id == $recipientId;

        if ($messageRequestSelf) {
            return response()->json(
                [
                    "message" => "No! Who the hell want to message themselves?",
                ],
                Response::HTTP_FORBIDDEN
            );
        }

        if ($messageRequestExists) {
            return response()->json(
                [
                    "message" => "Message request already exists.",
                ],
                Response::HTTP_CONFLICT
            );
        }

        $messageRequest = MessageRequest::create([
            "sender_id" => $sender->id,
            "recipient_id" => $recipientId,
            "status" => "pending",
        ]);

        //event(new MessageRequestSent($sender, $recipientId));
        broadcast(new MessageRequestSent($sender, $messageRequest))->toOthers();

        $messaging = app("firebase.messaging");
        $message = CloudMessage::new()
            // ->withNotification(
            //     Notification::create(
            //         "Message Request",
            //         Str::title("{$sender->profile->firstname}") . " sent you a message request."
            //     )
            // )
            ->withData([
                "type" => "message-request",
                "title" => "Message Request",
                "body" => Str::title($sender->profile->firstname) . " sent you a message request.",
                "sender_id" => auth()->id(),
                "firstname" => $sender->profile->firstname ?? "",
                "lastname" => $sender->profile->lastname ?? "",
                "avatar" => $sender->profile->avatar
                    ? Storage::temporaryUrl($sender->profile->avatar, now()->addDays(5))
                    : null,

                "id" => $messageRequest->id,
                "status" => "pending",
                "requested_at" => $messageRequest->created_at->toIso8601String(),
            ])
            ->toToken($recipient->fcm_token);

        $messaging->send($message);
        return response()->json(
            [
                "message" => "Message request sent!",
            ],
            Response::HTTP_CREATED
        );
    }

    public function acceptMessageRequest(Request $request): JsonResponse
    {
        $request->validate([
            "id" => "required|integer|exists:pgsql.message_requests,id",
        ]);

        $messageRequest = MessageRequest::where("id", $request->id)
            ->where("status", "pending")
            ->first();

        if (!$messageRequest) {
            return response()->json(
                ["message" => "Message request not found."],
                Response::HTTP_NOT_FOUND
            );
        }

        // Create new chat
        $chat = Chat::create([
            "chat_type" => "direct",
            "name" => null,
        ]);

        // Add participants
        ChatParticipant::insert([
            [
                "chat_id" => $chat->id,
                "user_id" => $messageRequest->sender_id,
                "role" => "member",
            ],
            [
                "chat_id" => $chat->id,
                "user_id" => $messageRequest->recipient_id,
                "role" => "member",
            ],
        ]);

        // Mark message request as accepted
        $messageRequest->update(["status" => "accepted"]);

        // Broadcast real-time chat creation
        broadcast(
            new ChatCreated($chat, $messageRequest->id, [
                $messageRequest->sender_id,
                $messageRequest->recipient_id,
            ])
        );

        $sender = Member::with("profile")->where("id", $messageRequest->sender_id)->first();

        $recipient = Member::with("profile")->where("id", $messageRequest->recipient_id)->first();

        $messaging = app("firebase.messaging");

        // Send notification to sender
        if ($sender && $sender->fcm_token) {
            $messageToSender = CloudMessage::new()
                ->withData([
                    "type" => "chat-created",
                    "title" => "Message Request Accepted",
                    "body" =>
                        Str::title($recipient->profile->firstname) .
                        " accepted your message request.",
                    "chat" => json_encode([
                        "id" => $chat->id,
                        "chat_type" => $chat->chat_type,
                        "name" => $chat->name,
                    ]),
                    "participants" => json_encode([
                        [
                            "id" => $recipient->id,
                            "firstname" => $recipient->profile->firstname,
                            "lastname" => $recipient->profile->lastname,
                            "avatar" => $recipient->profile->avatar
                                ? Storage::temporaryUrl(
                                    $recipient->profile->avatar,
                                    now()->addDays(5)
                                )
                                : null,
                        ],
                        [
                            "id" => $sender->id,
                            "firstname" => $sender->profile->firstname,
                            "lastname" => $sender->profile->lastname,
                            "avatar" => $sender->profile->avatar
                                ? Storage::temporaryUrl($sender->profile->avatar, now()->addDays(5))
                                : null,
                        ],
                    ]),
                    "message_request_id" => $messageRequest->id,
                ])
                ->toToken($sender->fcm_token);

            $messaging->send($messageToSender);
        }

        return response()->json(["message" => "Message request accepted."], Response::HTTP_OK);
    }

    public function declineMessageRequest(Request $request): JsonResponse
    {
        $request->validate([
            "id" => "required|integer|exists:pgsql.message_requests,id",
        ]);

        $id = $request->id;

        $messageRequest = MessageRequest::where("id", $id)->where("status", "pending")->first();

        if (!$messageRequest) {
            return response()->json(
                [
                    "message" => "Message request not found.",
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $messageRequest->update([
            "status" => "declined",
        ]);

        return response()->json(
            [
                "message" => "Message request declined.",
            ],
            Response::HTTP_OK
        );
    }
}
