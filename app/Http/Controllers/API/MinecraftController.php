<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MinecraftController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'subscribed']);
    }

    public function show(Request $request, $uuid)
    {
        $user = User::where('current_account', $uuid)
            ->firstOrFail();

        if (!$user->hasCapesAccess()) {
            return response()->json([
                'message' => '403 Forbidden'
            ], 403);
        }

        return response()->json([
            'cape' => $user->cape
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'uuid' => 'required|uuid'
        ]);

        $request->user()->fill([
            'current_account' => $validated['uuid']
        ])->save();

        return response()->json([
            'message' => '200 OK'
        ]);
    }

    public function destroy(Request $request)
    {
        $request->user()->fill([
            'current_account' => null
        ])->save();

        return response()->json([
            'message' => '200 OK'
        ]);
    }
}
