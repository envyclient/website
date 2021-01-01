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

    public function index()
    {
        return User::where('current_account', '<>', null)
            ->pluck('current_account')
            ->toArray();
    }

    public function show($uuid)
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

        $request->user()->update([
            'current_account' => $validated['uuid'],
        ]);

        return response()->json([
            'message' => '200 OK'
        ]);
    }

    public function destroy(Request $request)
    {
        $request->user()->update([
            'current_account' => null,
        ]);

        return response()->json([
            'message' => '200 OK'
        ]);
    }
}
