<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Http;

class DiscordHelper
{
    public static function getRedirectUrl(string $url): string
    {
        $id = config('services.discord.client_id');
        $url = urlencode($url);
        return "https://discord.com/api/oauth2/authorize?client_id=$id&redirect_uri=$url&response_type=code&scope=identify";
    }

    /**
     * @throws Exception
     */
    public static function getDiscordUser(string $code, string $redirect)
    {
        $response = Http::asForm()
            ->acceptJson()
            ->post('https://discord.com/api/oauth2/token', [
                'client_id' => config('services.discord.client_id'),
                'client_secret' => config('services.discord.client_secret'),
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $redirect,
                'scope' => 'identify',
            ]);

        if ($response->status() !== 200) {
            throw new Exception('Could not fetch discord access token.');
        }

        $response = Http::withToken(
            $response->json('access_token')
        )->get('https://discord.com/api/users/@me');

        // could not get discord account info
        if ($response->status() !== 200) {
            throw new Exception('Could not fetch discord user.');
        }

        return $response->json();
    }
}
