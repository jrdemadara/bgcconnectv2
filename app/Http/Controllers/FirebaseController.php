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
            "fcm_token" => "required|string",
        ]);

        $user = User::find(auth()->id());

        if ($user) {
            $user->fcm_token = $request->fcm_token;
            $user->save();
            return response()->json(
                [
                    "message" => "FCM token updated",
                ],
                Response::HTTP_CREATED
            );
        }

        return response()->json(
            [
                "message" => "error",
            ],
            Response::HTTP_NOT_FOUND
        );
    }
}
