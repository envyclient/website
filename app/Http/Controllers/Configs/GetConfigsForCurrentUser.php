<?php

namespace App\Http\Controllers\Configs;

use App\Http\Controllers\Controller;
use App\Http\Resources\Config as ConfigResource;
use App\Models\Config;
use Illuminate\Http\Request;

class GetConfigsForCurrentUser extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
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
