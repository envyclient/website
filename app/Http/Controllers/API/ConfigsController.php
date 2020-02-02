<?php

namespace App\Http\Controllers\API;

use App\Config;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfigsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        return Config::with('user')
            ->where('public', true)
            ->get();
    }

    public function getCurrentUserConfigs(Request $request)
    {
        return $request->user()->configs;
    }

    public function getConfigsByUser(Request $request, $name)
    {
        $user = User::where('name', $name)->firstOrFail();
        return $user->configs()
            ->where('public', true)
            ->get();
    }

    public function show(Request $request, $id)
    {
        return Config::with('user')
            ->where('id', $id)
            ->where('public', true)
            ->firstOrFail();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'data' => 'required|json',
            'public' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        $user = $request->user();

        if ($user->configs()->count() === Config::LIMIT) {
            return response()->json([
                'message' => '406 Not Acceptable'
            ], 406);
        }

        $config = new Config();
        $config->user_id = $user->id;
        $config->title = $request->title;
        $config->data = $request->data;
        if ($request->has('public')) {
            $config->public = $request->public;
        }
        $config->save();

        return response()->json([
            'message' => '201 Created'
        ], 201);
    }

    public function destroy(Request $request, $id)
    {
        $request->user()->configs()->findOrFail($id)->delete();
        return response()->json([
            'message' => '200 OK'
        ], 200);
    }
}
