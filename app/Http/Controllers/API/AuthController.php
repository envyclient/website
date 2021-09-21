<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request): UserResource|JsonResponse
    {
        $data = $this->validate($request, [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'hwid' => ['required', 'string', 'min:40', 'max:40'],
        ]);

        if (!auth()->attempt(request(['email', 'password']))) {
            return self::unauthorized();
        }

        return $this->returnUserObject($request->user(), $data['hwid']);
    }

    public function me(Request $request): UserResource|JsonResponse
    {
        $data = $this->validate($request, [
            'hwid' => ['required', 'string', 'min:40', 'max:40'],
        ]);

        $user = User::where('hwid', $data['hwid'])->firstOrFail();
        return $this->returnUserObject($user, $data['hwid']);
    }

    private static function returnUserObject(User $user, string $hwid): UserResource|JsonResponse
    {
        // check for duplicate hwid
        $userCheck = User::where('hwid', $hwid)->where('id', '<>', $user->id);
        if ($userCheck->exists()) {
            return response()->json(['message' => 'Duplicate hardware identification.'], 403);
        }

        // hwid mismatch
        if ($user->hwid !== null && $user->hwid !== $hwid) {
            return response()->json(['message' => 'Hardware Identification mismatch.'], 403);
        }

        // subscription check
        if (!$user->hasSubscription()) {
            return response()->json(['message' => 'User does not have subscription for the client.'], 403);
        }

        // ban check
        if ($user->banned) {
            return response()->json(['message' => 'Account banned.'], 403);
        }

        // update the users hwid
        $user->update([
            'hwid' => $hwid
        ]);

        return new UserResource($user);
    }
}
