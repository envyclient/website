<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class DiscordController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function login()
    {
        return Socialite::driver('discord')
            ->redirectUrl(config('services.discord.redirect_connect'))
            ->redirect();
    }

    public function redirect(Request $request)
    {
        try {
            $user = Socialite::driver('discord')
                ->redirectUrl(config('services.discord.redirect_connect'))
                ->user();
        } catch (Exception $e) {
            return redirect('discord')->with('error', 'Connection cancelled.');
        }

        // check if discord account is already used
        if (User::where('discord_id', $user->getId())
            ->where('id', '<>', $request->user()->id)
            ->exists()) {
            return redirect('discord')->with('error', 'Discord already linked to another account.');
        }

        // update user account with discord account info
        $request->user()->update([
            'discord_id' => $user->getId(),
            'discord_name' => $user->getNickname(),
        ]);

        return redirect('discord')->with('success', 'Discord account connected.');
    }

}