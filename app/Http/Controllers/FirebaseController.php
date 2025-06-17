<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FirebaseController extends Controller
{
    public function updateFCMToken(Request $request): JsonResponse
    {
        $request->validate([
            "fcmToken" => "required|string",
        ]);

        $user = User::find(auth()->id);
        $user->fcm_token = $request->fcmToken;
        $user->save();

        return response()->json(
            [
                "message" => "success",
            ],
            Response::HTTP_CREATED
        );
    }
}
