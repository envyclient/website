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
            'hwid' => 'required|string|min:40|max:40',
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

        return $this->returnUserObject(auth()->user(), $request->hwid, $request->account_name);
    }

    public function me(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hwid' => 'required|string|min:40|max:40',
            'account_name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        return $this->returnUserObject($request->user(), $request->hwid, $request->account_name);
    }

    private function returnUserObject($user, string $hwid, string $accountName)
    {
        // check for duplicate api_token
        $userCheck = User::where('api-token', $hwid);
        if ($userCheck->exists() && $userCheck->first()->id !== $user->id) {
            return response()->json(['message' => 'Duplicate hwid.'], 401);
        }

        // hwid mismatch
        if ($user->hwid !== null && $user->hwid !== $hwid) {
            return response()->json(['message' => 'User HWID has already been set.'], 401);
        }

        // subscription check
        if (!$user->hasSubscription()) {
            return response()->json(['message' => 'User does not have subscription for the client.'], 401);
        }

        // TODO: add this exact check to the frontend
        // expired subscription check
        if ($user->subscription()->whereDate('end_date', '<=', Carbon::today()->format('Y-m-d'))->exists()) {
            if (!$user->renewSubscription()) {
                return response()->json(['message' => 'User does not have subscription for the client.'], 401);
            }
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
            'api_token' => $hwid,
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
