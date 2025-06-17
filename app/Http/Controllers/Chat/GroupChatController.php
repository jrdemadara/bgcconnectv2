<?php

namespace App\Http\Controllers\Chat;

use App\Events\GroupChatInvite;
use App\Models\Chat;
use App\Models\ChatParticipant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class GroupChatController extends Controller
{
    public function createGroupChat(Request $request): JsonResponse
    {
        $request->validate([
            "groupChatName" => "required|string:255",
            "participants" => "required|array",
            "participants.*" => "integer",
        ]);

        // Check group name is exists
        if (Chat::where("name", $request->groupChatName)->exists()) {
            return response()->json(
                [
                    "message" => "error",
                ],
                Response::HTTP_CONFLICT
            );
        }

        // Create the group chat
        $groupChat = Chat::create([
            "chat_type" => "group",
            "name" => $request->grouChatName,
        ]);

        // Set group admin
        ChatParticipant::create([
            "chat_id" => $groupChat->id,
            "user_id" => auth()->id,
            "role" => "admin",
        ]);

        // Extract and save all members
        $participants = $request->participants;
        foreach ($participants as $participant) {
            ChatParticipant::create([
                "chat_id" => $groupChat->id,
                "user_id" => $$participant->id,
                "role" => "member",
            ]);

            // Broadcast event to participants
            broadcast(new GroupChatInvite($groupChat, $$participants));
        }

        return response()->json(
            [
                "message" => "success",
            ],
            Response::HTTP_CREATED
        );
    }

    public function acceptInvite(Request $request): JsonResponse
    {
        $request->validate([
            "chat_id" => "required|integer",
        ]);
    }
}
