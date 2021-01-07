<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class MinecraftController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'subscribed']);
    }

    public function show($uuid)
    {
        try {
            $user = User::where('current_account', $uuid)
                ->firstOrFail();

            $response = [
                'using' => true,
                'cape' => $user->hasCapesAccess() ? $user->cape : null,
            ];

        } catch (ModelNotFoundException $e) {
            $response = [
                'using' => false,
                'cape' => null,
            ];
        }

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
