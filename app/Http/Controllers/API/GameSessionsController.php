<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GameSessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        $user = $request->user();

        // update game session
        $session = $user->gameSessions()->findOrFail($id);
        $session->data = $request->data;
        $session->save();

        return response()->json([
            'message' => '200 OK'
        ], 200);
    }
}
