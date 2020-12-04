<?php

namespace App\Http\Controllers\Configs;

use App\Http\Controllers\Controller;
use App\Http\Resources\Config as ConfigResource;
use App\Models\Config;
use App\Models\User;
use Illuminate\Http\Request;

class GetConfigsForUser extends Controller
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
