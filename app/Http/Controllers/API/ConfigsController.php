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
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        if ($request->has('search')) {
            $name = $request->search;
            return ConfigResource::collection(
                Config::with('user:id,name')
                    ->where([
                        ['name', 'like', "%$name%"],
                        ['public', '=', true]
                    ])->withCount('favorites')
                    ->orderBy('favorites_count', 'desc')
                    ->paginate(Config::PAGE_LIMIT)
            );
        } else {
            return ConfigResource::collection(
                Config::with('user:id,name')
                    ->withCount('favorites')
                    ->where('public', true)
                    ->orderBy('favorites_count', 'desc')
                    ->paginate(Config::PAGE_LIMIT)
            );
        }
    }

    public function show($id)
    {
        return new ConfigResource(
            Config::with('user:id,name')
                ->where('id', $id)
                ->where('public', true)
                ->withCount('favorites')
                ->orderBy('favorites_count', 'desc')
                ->firstOrFail()
        );
    }

    public function store(Request $request)
    {
        // TODO: validate json to only be ARRAY
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:15',
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
                'message' => 'Config limit reached'
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

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:15',
            'data' => 'required|json',
            'public' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        $user = $request->user();

        // update config
        $config = $user->configs()->findOrFail($id);
        $config->name = $request->name;
        $config->data = $request->data;
        if ($request->has('public')) {
            $config->public = $request->public;
        }
        $config->save();

        return response()->json([
            'message' => '200 OK'
        ], 200);
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
        $data['favorites'] = ConfigResource::collection(
            $request->user()
                ->configs()
                ->withCount('favorites')
                ->orderBy('favorites_count', 'desc')
                ->paginate(Config::PAGE_LIMIT)
        );
        $data['self'] = ConfigResource::collection(
            $request->user()
                ->configs()
                ->withCount('favorites')
                ->orderBy('favorites_count', 'desc')
                ->paginate(Config::PAGE_LIMIT)
        );
        return $data;
    }

    public function getConfigsByUser(string $name)
    {
        $user = User::where('name', $name)->firstOrFail();
        return ConfigResource::collection(
            $user->configs()
                ->where('public', true)
                ->withCount('favorites')
                ->orderBy('favorites_count', 'desc')
                ->paginate(Config::PAGE_LIMIT)
        );
    }

    public function favorite(Request $request, $id)
    {
        $config = Config::findOrFail($id);
        $user = $request->user();

        // can not favorite own config
        if ($config->user_id === $user->id) {
            return response()->json([
                'message' => 'You can not favorite your own config.'
            ], 403);
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
