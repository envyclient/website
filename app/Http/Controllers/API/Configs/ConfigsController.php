<?php

namespace App\Http\Controllers\API\Configs;

use App\Http\Controllers\Controller;
use App\Http\Resources\Config as ConfigResource;
use App\Models\Config;
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

        $configs = Config::with('user')
            ->withCount('favorites')
            ->where('public', true)
            ->orderBy('favorites_count', 'desc');

        if ($request->has('search')) {
            $configs->where('name', 'like', "%$request->search%");
        }

        return ConfigResource::collection(
            $configs->paginate(Config::PAGE_LIMIT)
        );
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
            'public' => 'nullable|boolean',
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

        $config = Config::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'data' => $request->data,
            'public' => $request->has('public'),
        ]);

        return response()->json([
            'message' => '201 Created',
            'id' => $config->id,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:15',
            'data' => 'required|json',
            'public' => 'nullable|boolean',
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
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $request->user()->configs()->findOrFail($id)->delete();
        return response()->json([
            'message' => '200 OK'
        ], 200);
    }
}
