<?php

namespace App\Http\Controllers\API\Configs\Actions;

use App\Http\Controllers\API\Configs\ConfigsController;
use App\Models\Config;
use Illuminate\Http\Request;

class FavoriteConfig extends ConfigsController
{
    public function __construct()
    {
        parent::__construct();
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

        $user->toggleFavorite($config);

        return response()->json([
            'message' => '200 OK'
        ]);
    }
}
