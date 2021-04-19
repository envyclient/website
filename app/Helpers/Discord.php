<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class Discord
{
    const GUILD = '794374279395147777';
    const STANDARD = '794384676113481738';
    const PREMIUM = '794384624092446730';

    public static function sendWebhook(string $message)
    {
        HTTP::withBody(json_encode([
            'username' => 'Envy Client',
            'avatar_url' => asset('android-chrome-512.png'),
            'tts' => false,
            'content' => $message,
        ]), 'application/json')
            ->post(config('discord.webhook'));
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

    private static function updateRole(int $user, int $role, bool $remove = false): void
    {
        $token = config('discord.token');
        $endpoint = 'https://discord.com/api';
        $guild = self::GUILD;

        if ($remove) {
            $response = Http::withToken($token, 'Bot')
                ->delete("$endpoint/guilds/$guild/members/$user/roles/$role");
        } else {
            $response = Http::withToken($token, 'Bot')
                ->put("$endpoint/guilds/$guild/members/$user/roles/$role");
        }

        // rate limit
        /*if ($response->header('X-RateLimit-Remaining') == 0) {
            $sleep = $response->header('X-RateLimit-Reset-After');
            sleep(intval($sleep));
        }*/
    }
}
