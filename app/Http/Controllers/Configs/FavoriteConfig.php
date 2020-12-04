<?php

namespace App\Http\Controllers\Configs;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\Request;

class FavoriteConfig extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    public function __invoke(Request $request, $id)
    {
        $config = Config::findOrFail($id);
        $user = $request->user();

        // can not favorite own config
        if ($config->user_id === $user->id) {
            return response()->json([
                'message' => 'You can not favorite your own config.'
            ], 403);
        }

        if ($user->hasFavorited($config)) {
            $user->unfavorite($config);
        } else {
            $user->favorite($config);
        }

        return response()->json([
            'message' => '200 OK'
        ], 200);
    }
}
