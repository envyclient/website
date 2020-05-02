<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only('me');
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->returnUserObject();
    }

    public function me()
    {
        return $this->returnUserObject();
    }

    private function returnUserObject()
    {
        $user = auth()->user();
        return response()->json([
            'name' => $user->name,
            'api_token' => $user->api_token,
            'ban_reason' => $user->ban_reason,
            'created_at' => $user->created_at
        ]);
    }
}
