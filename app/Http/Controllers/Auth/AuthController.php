<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        // Validate input
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find the member by phone number
        $member = Member::where('phone', $request->phone)->first();

        // Check password
        if (!$member || !Hash::check($request->password, $member->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone number or password.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Generate a Sanctum token
        $token = $member->createToken('access-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'access_token' => $token,
            'member' => $member,
        ], Response::HTTP_OK); // 200 OK
    }

    public function logout(Request $request)
    {
        // Ensure user is authenticated
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Revoke the user's token
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ], Response::HTTP_NO_CONTENT);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'member' => $request->user(),
        ], Response::HTTP_OK); // 200 OK
    }
}
