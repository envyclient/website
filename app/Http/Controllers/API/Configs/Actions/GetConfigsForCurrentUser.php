<?php

namespace App\Http\Controllers\API\Configs\Actions;

use App\Http\Controllers\API\Configs\ConfigsController;
use App\Http\Resources\Config as ConfigResource;
use App\Models\Config;
use Illuminate\Http\Request;

class GetConfigsForCurrentUser extends ConfigsController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __invoke(Request $request)
    {
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

        return ConfigResource::collection($data);
    }
}
