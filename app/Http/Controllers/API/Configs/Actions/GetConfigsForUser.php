<?php

namespace App\Http\Controllers\API\Configs\Actions;

use App\Http\Controllers\API\Configs\ConfigsController;
use App\Http\Resources\Config as ConfigResource;
use App\Models\Config;
use App\Models\User;
use Illuminate\Http\Request;

class GetConfigsForUser extends ConfigsController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function __invoke(Request $request, $name)
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
}
