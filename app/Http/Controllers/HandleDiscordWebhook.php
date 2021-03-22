<?php

namespace App\Http\Controllers;

use App\Helpers\Discord;
use App\Models\User;
use Illuminate\Http\Request;

class HandleDiscordWebhook extends Controller
{
    private array $roles;

    public function __construct()
    {
        $this->roles = [
            'standard' => intval(config('discord.guild.roles.standard')),
            'premium' => intval(config('discord.guild.roles.premium')),
        ];
    }

    public function __invoke(Request $request)
    {
        if (!$request->has('discord_id')) {
            return self::bad();
        }

        $user = User::where('discord_id', $request->json('discord_id'))
            ->findOrFail();

        // user has an active subscription
        if ($user->subscription->deleted_at === null) {
            switch ($user->subscription->plan->id) {
                case 1:
                case 3:
                {
                    Discord::updateRole($user->discord_id, $this->roles['premium']);
                    Discord::sendWebhook("Gave <@$user->discord_id> the premium role.");
                    break;
                }
                case 2:
                {
                    Discord::updateRole($user->discord_id, $this->roles['standard']);
                    Discord::sendWebhook("Gave <@$user->discord_id> the standard role.");
                    break;
                }
            }
        } else { // user no longer has an active subscription
            Discord::updateRole($user->discord_id, $this->roles['standard'], true);
            Discord::updateRole($user->discord_id, $this->roles['premium'], true);
            Discord::sendWebhook("Removed roles from <@$user->discord_id>.");
        }

        return response()->noContent();
    }
}
