<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class SearchMemberController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'key' => 'required|string',
        ]);

        $members = Member::with('profile:user_id,firstname,lastname,avatar')
            ->select('id')
            ->where('level', '>=', 3)
            ->where('is_active', true)
            ->where(function ($query) use ($request) {
                $query->where('code', $request->key)
                    ->orWhere('phone', $request->key)
                    ->orWhereHas('profile', function ($q) use ($request) {
                        $q->where('firstname', 'like', '%' . $request->key . '%')
                            ->orWhere('lastname', 'like', '%' . $request->key . '%');
                    });
            })
            ->get();

        $data = $members->map(function ($member) {
            $profile = $member->profile;

            return [
                'id' => $member->id,
                'firstname' => $profile?->firstname,
                'lastname' => $profile?->lastname,
                'avatar' => $profile->avatar ? Storage::temporaryUrl($profile->avatar, now()->addDays(5)) : null,
            ];
        });

        return response()->json([
            'data' => $data
        ], Response::HTTP_OK);
    }
}
