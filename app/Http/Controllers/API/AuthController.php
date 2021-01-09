<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('api');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'hwid' => 'required|string|min:40|max:40',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        if (!$token = auth()->attempt(request(['email', 'password']))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->returnUserObject(auth()->user(), $request->hwid);
    }

    public function me(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hwid' => 'required|string|min:40|max:40',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        $user = User::where('hwid', $request->hwid)->firstOrFail();
        return $this->returnUserObject($user, $user->hwid);
    }

    private static function returnUserObject($user, string $hwid)
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

        // disable check
        if ($user->disabled) {
            return response()->json(['message' => 'Account disabled.'], 403);
        }

        // fill users hwid
        $user->update([
            'hwid' => $hwid
        ]);

        return response()->json([
            'name' => $user->name,
            'api_token' => $user->api_token,
            'date' => $user->created_at->diffForHumans(),
        ]);
    }

}
