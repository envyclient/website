<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class DiscordController extends Controller
{
    public function login()
    {
        return Socialite::driver('discord')->redirect();
    }

    public function redirect()
    {
        try {
            $user = Socialite::driver('discord')->user();
        } catch (Exception) {
            return redirect('login');
        }

        // generate a random number
        $rand = random_int(1, 99999);

        // create the user
        $user = User::firstOrCreate([
            'discord_id' => $user->getId(),
        ], [
            'name' => "envy_$rand",
            'email' => "envy_$rand@envyclient.com",
            'password' => null,
            'email_verified_at' => now(),
            'discord_name' => $user->getNickname(),
        ]);

        Auth::login($user, true);

        return redirect(RouteServiceProvider::HOME);
    }
}
