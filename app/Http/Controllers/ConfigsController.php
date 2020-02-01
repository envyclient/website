<?php

namespace App\Http\Controllers;

use App\Config;
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
            ->orderBy('stars', 'desc')
            ->get();
    }

    public function getConfigsByUser(Request $request, $name)
    {
        $user = User::where('name', $name);

        if (!$user->exists()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        return $user->configs()
            ->where('public', true)
            ->get();
    }

    public function show(Request $request, $id)
    {
        $config = Config::with('user')
            ->where('public', true)
            ->where('id', $id);

        if (!$config->exists()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        return $config->get();
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
}
