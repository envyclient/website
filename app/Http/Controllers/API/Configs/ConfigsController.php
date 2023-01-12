<?php

namespace App\Http\Controllers\API\Configs;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConfigRequest;
use App\Http\Resources\ConfigResource;
use App\Models\Config;
use App\Models\Version;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ConfigsController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->validate($request, [
            'search' => ['nullable', 'string'],
        ]);

        $configs = Config::query()
            ->with(['user:id,name', 'version:id,name'])
            ->withCount('favorites')
            ->where('public', true)
            ->orderByDesc('official')
            ->orderByDesc('favorites_count')
            ->when($request->has('search'), function (Builder $query) use ($data) {
                $query->where('name', 'like', "%{$data['search']}%")
                    ->orWhereRelation('user', 'name', 'like', "%{$data['search']}%");
            });

        return ConfigResource::collection(
            $configs->paginate(Config::PAGE_LIMIT)
        );
    }

    public function show(int $id)
    {
        return new ConfigResource(
            Config::query()
                ->with(['user:id,name', 'version:id,name'])
                ->where('id', $id)
                ->where('public', true)
                ->withCount('favorites')
                ->orderBy('favorites_count', 'desc')
                ->firstOrFail()
        );
    }

    public function store(StoreConfigRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        // checking if the user has reached their config limit
        if ($user->configs()->count() >= $user->subscription->plan->config_limit) {
            return response()->json([
                'message' => 'Config limit reached.',
            ], 406);
        }

        // creating a new config for the user
        $config = Config::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'version_id' => Version::where('name', "Envy {$data['version']}")->value('id'),
            'data' => $data['data'],
            'public' => boolval($data['public']),
        ]);

        return response()->json([
            'message' => '201 Created',
            'id' => $config->id,
        ], 201);
    }

    public function update(StoreConfigRequest $request, int $id)
    {
        $data = $request->validated();

        // update config
        $request->user()
            ->configs()
            ->findOrFail($id)
            ->update([
                'name' => $data['name'],
                'version_id' => Version::where('name', "Envy {$data['version']}")->value('id'),
                'data' => $data['data'],
                'public' => boolval($data['public']),
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
