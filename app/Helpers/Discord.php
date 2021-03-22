<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class Discord
{
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

    public static function updateRole(int $user, int $role, bool $remove = false): void
    {
        $token = config('discord.token');
        $endpoint = config('discord.endpoint');
        $guild = config('discord.guild.id');

        if ($remove) {
            $response = Http::withToken($token, 'Bot')
                ->delete("$endpoint/guilds/$guild/members/$user/roles/$role");
        } else {
            $response = Http::withToken($token, 'Bot')
                ->put("$guild/guilds/$guild/members/$user/roles/$role");
        }

        // rate limit
        /*if ($response->header('X-RateLimit-Remaining') == 0) {
            $sleep = $response->header('X-RateLimit-Reset-After');
            sleep(intval($sleep));
        }*/
    }
}
