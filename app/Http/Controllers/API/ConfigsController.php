<?php

namespace App\Http\Controllers\API;

use App\Config;
use App\Http\Controllers\Controller;
use App\Rules\Config as ConfigRule;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfigsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Get all public configs.
     */
    public function index()
    {
        return Config::with('user:id,name')
            ->where('public', true)
            ->get();
    }

    public function show(Request $request, $id)
    {
        return Config::with('user:id,name')
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
        if ($user->configs()->count() === $user->getConfigLimit()) {
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

    public function getCurrentUserConfigs(Request $request)
    {
        // TODO: return user configs, user favorite configs, and followed users configs
        $data = [];
        $data['self'] = $request->user()->configs;
        $data['favorites'] = $request->user()->favorites();
        return $data;
    }

    /**
     * Get config by owners username.
     */
    public function getConfigsByUser(Request $request, $name)
    {
        $user = User::where('name', $request->name)->firstOrFail();
        return $user->configs()
            ->where('public', true)
            ->get();
    }

    /**
     * Favorite a config by its id.
     */
    public function favorite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'config' => ['required', 'int', new ConfigRule] // checks if config exists & is public
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        $config = Config::find($request->config);
        $user = $request->user();

        if ($user->hasFavorited($config)) {
            $user->unfavorite($config);
        } else {
            $user->favorite($config);
        }

        return response()->json([
            'message' => '200 OK'
        ], 200);
    }
}
