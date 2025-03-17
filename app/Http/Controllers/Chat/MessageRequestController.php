<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\MessageRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageRequestController extends Controller
{
    public function messageRequest(Request $request)
    {
        $request->validate([
            'recipientId' => 'required|integer|exists:users,id',
        ]);

        $sender = auth()->user();
        $recipientId = $request->recipientId;

        // Check if a request already exists
        $existingRequest = MessageRequest::where('sender_id', $sender->id)
            ->where('recipient_id', $recipientId)
            ->first();

        if ($existingRequest) {
            return response()->json([
                'message' => 'Message request already sent.'
            ], 409);
        }

        return response()->json([
            'message' => 'No existing chat found'
        ]);
    }
}
