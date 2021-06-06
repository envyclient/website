<?php

namespace App\Http\Controllers\API\Configs;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConfigRequest;
use App\Models\Config;
use App\Models\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfigsController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return self::bad();
        }

        $validated = $validator->validated();
        $configs = Config::with(['user:id,name', 'version:id,name'])
            ->withCount('favorites')
            ->where('public', true)
            ->orderByDesc('official')
            ->orderByDesc('favorites_count');

        if ($request->has('search')) {
            $configs->where('name', 'like', "%{$validated['search']}%")
                ->orWhereHas('user', function ($query) use ($validated) {
                    $query->where('name', 'like', "%{$validated['search']}%");
                });
        }

        return \App\Http\Resources\Config::collection(
            $configs->paginate(Config::PAGE_LIMIT)
        );
    }

    public function show(int $id)
    {
        return new \App\Http\Resources\Config(
            Config::with(['user:id,name', 'version:id,name'])
                ->where('id', $id)
                ->where('public', true)
                ->withCount('favorites')
                ->orderBy('favorites_count', 'desc')
                ->firstOrFail()
        );
    }

    public function store(StoreConfigRequest $request)
    {
        $validated = $request->validated();

        $user = $request->user();
        if ($user->configs()->count() >= $user->getConfigLimit()) {
            return response()->json([
                'message' => 'Config limit reached'
            ], 406);
        }

        $config = Config::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'version_id' => Version::where('name', "Envy {$validated['version']}")->first()->id,
            'data' => $validated['data'],
            'public' => boolval($validated['public']),
        ]);

        return response()->json([
            'message' => '201 Created',
            'id' => $config->id,
        ], 201);
    }

    public function update(StoreConfigRequest $request, int $id)
    {
        $validated = $request->validated();

        // update config
        $request->user()
            ->configs()
            ->findOrFail($id)
            ->update([
                'name' => $validated['name'],
                'version_id' => Version::where('name', "Envy {$validated['version']}")->first()->id,
                'data' => $validated['data'],
                'public' => $request->has('public'),
            ]);

        return self::ok();
    }

    public function destroy(Request $request, int $id)
    {
        // delete config
        $request->user()
            ->configs()
            ->findOrFail($id)
            ->delete();

        return self::ok();
    }
}
