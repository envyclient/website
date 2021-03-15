<?php

namespace App\Http\Controllers\API\Configs\Actions;

use App\Http\Controllers\API\Configs\ConfigsController;
use App\Http\Resources\Config as ConfigResource;
use App\Models\Config;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GetConfigsForUser extends ConfigsController
{
    public function __invoke(Request $request, $name = null): JsonResponse|AnonymousResourceCollection
    {
        return is_null($name)
            ? self::getConfigsForAuthUser($request->user())
            : self::getConfigsForSearchUser($request);
    }

    public static function getConfigsForAuthUser(User $user): AnonymousResourceCollection
    {
        return ConfigResource::collection(
            $user->configs()
                ->where('public', true)
                ->withCount('favorites')
                ->orderBy('favorites_count', 'desc')
        );
    }

    public static function getConfigsForSearchUser(Request $request): JsonResponse|AnonymousResourceCollection
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            self::bad();
        }

        $data = collect();
        foreach ($request->user()
                     ->configs()
                     ->withCount('favorites')
                     ->orderBy('favorites_count', 'desc')
                     ->get() as $config) {
            $data->push($config);
        }

        foreach ($request->user()
                     ->getFavoriteItems(Config::class)
                     ->withCount('favorites')
                     ->orderBy('favorites_count', 'desc')
                     ->get() as $config) {
            $data->push($config);
        }

        if ($request->has('search')) {
            $data = $data->filter(function ($value, $key) use ($request) {
                return Str::contains(
                    strtolower($value['name']),
                    strtolower($request->search)
                );
            });
        }

        return ConfigResource::collection($data);
    }

}
