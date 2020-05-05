<?php

namespace App\Http\Controllers\API;

use App\Config;
use App\Http\Controllers\Controller;
use App\Http\Resources\Config as ConfigResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfigsController extends Controller
{
    // TODO: sort by favorites
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        return ConfigResource::collection(
            Config::with('user:id,name')
                ->where('public', true)
                ->paginate(Config::PAGE_LIMIT)
        );
    }

    public function show(Request $request, $id)
    {
        return new ConfigResource(
            Config::with('user:id,name')
                ->where('id', $id)
                ->where('public', true)
                ->firstOrFail()
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
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
        $config->name = $request->name;
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
        $data['favorites'] = $request->user()->favorites()->paginate(Config::PAGE_LIMIT);
        $data['self'] = $request->user()->configs()->paginate(Config::PAGE_LIMIT);
        return $data;
    }

    public function getConfigsByUser(Request $request, string $name)
    {
        $user = User::where('name', $name)->firstOrFail();
        return ConfigResource::collection(
            $user->configs()
                ->where('public', true)
                ->paginate(Config::PAGE_LIMIT)
        );
    }

    public function searchConfigByName(Request $request, string $name)
    {
        return ConfigResource::collection(
            Config::where([
                ['name', 'like', "%$name%"],
                ['public', '=', true]
            ])->paginate(Config::PAGE_LIMIT)
        );
    }

    public function favorite(Request $request, $id)
    {
        $config = Config::findOrFail($id);
        $user = $request->user();

        // can not favorite own config
        if ($config->user_id === $user->id) {
            return response()->json([
                'message' => '409 Conflict'
            ], 409);
        }

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
