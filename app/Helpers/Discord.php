<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class Discord
{
    const GUILD = '794374279395147777';
    const STANDARD = '794384676113481738';
    const PREMIUM = '794384624092446730';

    /**
     * Send a discord webhook.
     *
     * @param string $message the message contained in the webhook
     */
    public static function sendWebhook(string $message)
    {
        HTTP::withBody(json_encode([
            'username' => 'Envy Client',
            'avatar_url' => asset('android-chrome-512.png'),
            'tts' => false,
            'content' => $message,
        ]), 'application/json')
            ->post(config('services.discord.webhook'));
    }

    public static function handleDiscordRoles(User $user, callable $callback)
    {
        // check if user does not discord linked
        if ($user->discord_id === null) {
            return;
        }

        // user has an active subscription
        if ($user->hasSubscription()) {
            switch ($user->subscription->plan->id) {
                case 1:
                case 3:
                {
                    self::updateRole($user->discord_id, self::PREMIUM);
                    break;
                }
                case 2:
                {
                    self::updateRole($user->discord_id, self::STANDARD);
                    break;
                }
            }
        } else { // user no longer has an active subscription
            self::updateRole($user->discord_id, self::STANDARD, true);
            self::updateRole($user->discord_id, self::PREMIUM, true);
        }

        self::sendWebhook($callback($user->discord_id, $user->plan?->name));
    }

    /**
     * Update a users discord role.
     *
     * @param int $user the id of the user
     * @param int $role the id of the role
     * @param bool $remove whether to remove the specified role
     */
    private static function updateRole(int $user, int $role, bool $remove = false): void
    {
        $token = config('services.discord.token');
        $endpoint = 'https://discord.com/api';
        $guild = self::GUILD;

        $request = Http::withToken($token, 'Bot');

        if ($remove) {
            $request->delete("$endpoint/guilds/$guild/members/$user/roles/$role");
        } else {
            $request->put("$endpoint/guilds/$guild/members/$user/roles/$role");
        }
    }
}
