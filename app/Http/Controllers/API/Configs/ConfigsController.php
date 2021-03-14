<?php

namespace App\Http\Controllers\API\Configs;

use App\Http\Controllers\Controller;
use App\Http\Resources\Config as ConfigResource;
use App\Models\Config;
use App\Models\Version;
use App\Rules\ValidVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfigsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'subscribed']);
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            self::bad();
        }

        $configs = Config::with(['user:id,name', 'version:id,name'])
            ->withCount('favorites')
            ->where('public', true)
            ->orderByDesc('official')
            ->orderByDesc('favorites_count');

        if ($request->has('search')) {
            $configs->where('name', 'like', "%$request->search%")
                ->orWhereHas('user', function ($query) use ($request) {
                    $query->where('name', 'like', "%$request->search%");
                });
        }

        return ConfigResource::collection(
            $configs->paginate(Config::PAGE_LIMIT)
        );
    }

    public function show($id)
    {
        return new ConfigResource(
            Config::with(['user:id,name', 'version:id,name'])
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
            'version' => ['required', 'string', new ValidVersion],
            'data' => 'required|json',
            'public' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            self::bad();
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
            'version_id' => Version::where('name', "Envy v$request->version")->first()->id,
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
            'version' => ['required', 'string', new ValidVersion],
            'data' => 'required|json',
            'public' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            self::bad();
        }

        // update config
        $request->user()
            ->configs()
            ->findOrFail($id)
            ->update([
                'name' => $request->name,
                'version_id' => Version::where('name', "Envy v$request->version")->first()->id,
                'data' => $request->data,
                'public' => $request->has('public'),
            ]);

        return self::ok();
    }

    public function destroy(Request $request, $id)
    {
        // delete config
        $request->user()
            ->configs()
            ->findOrFail($id)
            ->delete();

        return self::ok();
    }
}
