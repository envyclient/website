<?php

namespace App\Helpers;

use App\Jobs\SendDiscordWebhookJob;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Http;

class DiscordHelper
{
    const GUILD = '794374279395147777';
    const STANDARD = '794384676113481738';
    const PREMIUM = '794384624092446730';

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

    public static function isAccountAlreadyLinked(string $discordId, int $userId): bool
    {
        return User::where('discord_id', $discordId)
            ->where('id', '<>', $userId)
            ->exists();
    }

    public static function handleDiscordRoles(User $user, callable $callback): void
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

        SendDiscordWebhookJob::dispatch($callback($user->discord_id, $user->subscription?->plan->name));
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
