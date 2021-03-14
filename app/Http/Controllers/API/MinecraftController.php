<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MinecraftController extends Controller
{
    public function show($uuid)
    {
        $user = User::where('current_account', $uuid)
            ->firstOrFail();

        $response = [
            'using' => true,
            'cape' => $user->hasCapesAccess() ? $user->cape : null,
        ];

        return response()->json($response);
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'uuid' => 'required|uuid'
        ]);

        $request->user()->update([
            'current_account' => $validated['uuid'],
        ]);

        return self::ok();
    }

    public function destroy(Request $request)
    {
        $request->user()->update([
            'current_account' => null,
        ]);

        return self::ok();
    }
}
