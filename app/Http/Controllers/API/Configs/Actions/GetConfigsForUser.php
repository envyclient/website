<?php

namespace App\Http\Controllers\API\Configs\Actions;

use App\Http\Controllers\API\Configs\ConfigsController;
use App\Http\Resources\ConfigResource;
use App\Models\Config;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetConfigsForUser extends ConfigsController
{
    public function __invoke(Request $request, $name = null): AnonymousResourceCollection
    {
        return is_null($name)
            ? self::getConfigsForAuthUser($request->user())
            : self::getConfigsForSearchUser(User::where('name', $name)->firstOrFail());
    }

    public static function getConfigsForAuthUser(User $user): AnonymousResourceCollection
    {
        $data = collect();
        foreach ($user->configs()
            ->with(['user:id,name', 'version:id,name'])
            ->withCount('favorites')
            ->orderBy('favorites_count', 'desc')
            ->get() as $config) {
            $data->push($config);
        }

        foreach ($user->getFavoriteItems(Config::class)
            ->with(['user:id,name', 'version:id,name'])
            ->withCount('favorites')
            ->orderBy('favorites_count', 'desc')
            ->get() as $config) {
            $data->push($config);
        }

        return ConfigResource::collection($data);
    }

    public static function getConfigsForSearchUser(User $user): AnonymousResourceCollection
    {
        return ConfigResource::collection(
            $user->configs()
                ->with(['user:id,name', 'version:id,name'])
                ->where('public', true)
                ->withCount('favorites')
                ->orderBy('favorites_count', 'desc')
                ->paginate(Config::PAGE_LIMIT)
        );
    }
}
