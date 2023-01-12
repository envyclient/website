<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $data = $this->validate($request, [
            'uuid' => ['required', 'uuid'],
        ]);

        $request->user()->update([
            'current_account' => $data['uuid'],
        ]);

        return self::ok();
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->user()->update([
            'current_account' => null,
        ]);

        return self::ok();
    }
}
