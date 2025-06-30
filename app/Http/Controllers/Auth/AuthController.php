<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            "phone" => "required|string",
            "password" => "required|string",
            "fcmToken" => "required|string",
            "deviceId" => "required|string",
            "deviceName" => "required|string",
            "loginLat" => "required|numeric|decimal:1,6",
            "loginLon" => "required|numeric|decimal:1,6",
        ]);

        $user = Member::where("phone", $request->phone)->with("profile")->with("devices")->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Invalid phone number or password.",
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        // Update FCM Token
        $user->fcm_token = $request->fcmToken;
        $user->save();

        // Create or Update device
        $device = UserDevice::updateOrCreate(
            [
                "device_id" => $request->deviceId,
                "user_id" => $user->id,
            ],
            [
                "name" => $request->deviceName,
                "login_lat" => $request->loginLat,
                "login_lon" => $request->loginLon,
                "is_online" => true,
            ]
        );

        // Generate a Sanctum token
        $token = $user->createToken("access-token")->plainTextToken;

        return response()->json(
            [
                "success" => true,
                "access_token" => $token,
                "data" => [
                    "id" => $user->id,
                    "code" => $user->code,
                    "phone" => $user->phone,
                    "points" => $user->points,
                    "level" => $user->level,
                    "firstname" => $user->profile->firstname ?? "",
                    "lastname" => $user->profile->lastname ?? "",
                    "middlename" => $user->profile->middlename ?? "",
                    "extension" => $user->profile->extension ?? "",
                    "avatar" => $user->profile->avatar
                        ? Storage::temporaryUrl($user->profile->avatar, now()->addDays(5))
                        : null,
                ],
            ],
            Response::HTTP_OK
        );
    }

    public function logout(Request $request)
    {
        // Ensure user is authenticated
        if (!$request->user()) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Unauthorized",
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        // Revoke the user's token
        $request->user()->tokens()->delete();

        return response()->json(
            [
                "success" => true,
                "message" => "Logged out successfully",
            ],
            Response::HTTP_NO_CONTENT
        );
    }

    public function profile(Request $request)
    {
        return response()->json(
            [
                "success" => true,
                "member" => $request->user(),
            ],
            Response::HTTP_OK
        ); // 200 OK
    }
}
