<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only('me');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'hwid' => 'required|string|min:40|max:40'
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
            'hwid' => 'required|string|min:40|max:40'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        return $this->returnUserObject($request->user(), $request->hwid);
    }

    private function returnUserObject($user, string $hwid)
    {
        if ($user->hwid === null) {
            $user->fill([
                'hwid' => $hwid
            ])->save();
        } else if ($user->hwid !== request('hwid')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'name' => $user->name,
            'api_token' => $user->api_token,
            'ban_reason' => $user->ban_reason,
            'created_at' => $user->created_at
        ]);
    }
}
