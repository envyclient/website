<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MinecraftController extends Controller
{
    public function show(string $uuid): JsonResponse
    {
        $user = User::where('current_account', $uuid)->firstOrFail();
        return response()->json([
            'using' => true,
            'cape' => $user->hasCapesAccess() ? $user->cape : null,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'uuid' => ['required', 'uuid'],
        ]);

        if ($validator->fails()) {
            return self::bad();
        }

        $request->user()->update([
            'current_account' => $request->uuid,
        ]);

        return self::ok();
    }

    public function destroy(): JsonResponse
    {
        auth()->user()->update([
            'current_account' => null,
        ]);
        return self::ok();
    }
}
