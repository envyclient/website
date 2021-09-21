<?php

namespace App\Http\Controllers\API\Configs\Actions;

use App\Http\Controllers\API\Configs\ConfigsController;
use App\Models\Config;
use Illuminate\Http\Request;

class FavoriteConfig extends ConfigsController
{
    public function __invoke(Request $request, Config $config)
    {
        // can not favorite own config
        $this->authorize('favorite', $config);

        // favorite or unfavorite the config depending on ont the status
        $request->user()->toggleFavorite($config);

        return self::ok();
    }
}
