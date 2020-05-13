<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('api')->only('login');
        $this->middleware('auth:api')->only('me');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'api_token' => 'required|string|min:40|max:40',
            'account_name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        if (!$token = auth()->attempt(request(['email', 'password']))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->returnUserObject(auth()->user(), $request->api_token, $request->account_name);
    }

    public function me(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        $user = $request->user();
        return $this->returnUserObject($user, $user->api_token, $request->account_name);
    }

    private function returnUserObject($user, string $apiToken, string $accountName)
    {
        // check for duplicate api_token
        $userCheck = User::where('api_token', $apiToken);
        if ($userCheck->exists() && $userCheck->first()->id !== $user->id) {
            return response()->json(['message' => 'Duplicate hwid.'], 401);
        }

        // hwid mismatch
        if ($user->hwid !== null && $user->hwid !== $apiToken) {
            return response()->json(['message' => 'User HWID has already been set.'], 401);
        }

        // subscription check
        if (!$user->hasSubscription()) {
            return response()->json(['message' => 'User does not have subscription for the client.'], 401);
        }

        // ban check
        if ($user->isBanned()) {
            return response()->json(['message' => 'User is banned.'], 401);
        }

        // disable check
        if ($user->disabled) {
            return response()->json(['message' => 'User has disabled their account.'], 401);
        }

        // fill users hwid
        $user->fill([
            'api_token' => $apiToken,
            'last_launch_user' => $accountName,
            'last_launch_at' => Carbon::now()
        ])->save();

        return response()->json([
            'name' => $user->name,
            'api_token' => $user->api_token,
            'ban_reason' => $user->ban_reason,
            'created_at' => $user->created_at
        ]);
    }
}
