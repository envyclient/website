<?php

namespace App\Http\Controllers\API\Actions;

use App\Helpers\Discord;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class HandleDiscordWebhook extends Controller
{
    public function __invoke(Request $request)
    {
        if (!$request->has('discord_id')) {
            return self::bad();
        }

        /** @var User $user */
        $user = User::with('subscription')
            ->where('discord_id', $request->input('discord_id'))
            ->firstOrFail();

        Discord::handleDiscordRoles($user, function (string $discord, string $plan = null) {
            return "<@$discord> has join the server, so I synchronized their role.";
        });

        return response()->noContent();
    }
}
