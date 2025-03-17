<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\MessageRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DirectChatController extends Controller
{
    public function messageRequest(Request $request)
    {
        $request->validate([
            'recipientId' => 'required|integer|exists:users,id',
        ]);

        $sender = auth()->user();
        $recipientId = $request->recipientId;

        // Check if a direct chat exists
        $chat = Chat::where('chat_type', 'direct')
            ->whereHas('chat_participants', function ($query) use ($sender) {
                $query->where('user_id', $sender->id);
            })
            ->whereHas('chat_participants', function ($query) use ($recipientId) {
                $query->where('user_id', $recipientId);
            })
            ->first();

        if ($chat) {
            return response()->json([
                'success' => true,
                'message' => 'Chat already exists',
                'chat_id' => $chat->id
            ], Response::HTTP_OK);
        } else {

        }

        return response()->json([
            'message' => 'No existing chat found'
        ]);
    }
}
