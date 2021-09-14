<?php

namespace App\Http\Controllers;

use App\Events\DiscordAccountConnectedEvent;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Exception;
use Laravel\Socialite\Facades\Socialite;

class DiscordController extends Controller
{
    public function login()
    {
        return Socialite::driver('discord')
            ->redirectUrl(config('services.discord.redirect_connect'))
            ->redirect();
    }

    public function redirect()
    {
        try {
            $discordUser = Socialite::driver('discord')
                ->redirectUrl(config('services.discord.redirect_connect'))
                ->user();
        } catch (Exception) {
            return redirect(RouteServiceProvider::HOME)->with('error', 'Connection cancelled.');
        }

        // check if discord account is already used
        if (self::isAccountAlreadyLinked($discordUser->getId(), auth()->id())) {
            return redirect(RouteServiceProvider::HOME)->with('error', 'Discord already linked to another account.');
        }

        // update user account with discord account info
        auth()->user()->update([
            'discord_id' => $discordUser->getId(),
            'discord_name' => $discordUser->getNickname(),
        ]);

        // broadcast the discord account connected event
        event(new DiscordAccountConnectedEvent(auth()->user()));

        return redirect(RouteServiceProvider::HOME)->with('success', 'Discord account connected.');
    }

    private static function isAccountAlreadyLinked(string $discordId, int $userId)
    {
        return User::where('discord_id', $discordId)
            ->where('id', '<>', $userId)
            ->exists();
    }
}
