<?php

namespace App\Http\Controllers\Chat;

use App\Events\MessageRequestSent;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MessageRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MessageRequestController extends Controller
{
    public function messageRequest(Request $request): JsonResponse
    {
        $request->validate([
            'recipientId' => 'required|integer|exists:users,id',
        ]);

        $sender = Member::with('profile')->find(auth()->id());
        $recipientId = $request->recipientId;

        $messageRequestExists = MessageRequest::where('sender_id', $sender->id)
            ->where('recipient_id', $recipientId)
            ->exists();

        $messageRequestSelf = $sender->id == $recipientId;

        if ($messageRequestSelf) {
            return response()->json([
                'message' => 'No! Who the hell want to message themselves?'
            ], Response::HTTP_FORBIDDEN);
        }

        if ($messageRequestExists) {
            return response()->json([
                'message' => 'Message request already exists.'
            ], Response::HTTP_CONFLICT);
        }

        $sessageRequest = MessageRequest::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipientId,
            'status' => 'pending',
        ]);

        event(new MessageRequestSent($sender, $recipientId));

        return response()->json([
            'message' => 'Message request sent!'
        ], Response::HTTP_CREATED);
    }
}
