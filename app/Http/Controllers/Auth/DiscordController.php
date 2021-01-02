<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class DiscordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function login()
    {
        return Socialite::driver('discord')->redirect();
    }

    public function redirect()
    {
        try {
            $user = Socialite::driver('discord')->user();
        } catch (Exception $e) {
            // since the user clicked cancel we redirect them to login page
            return redirect('login');
        }

        $user = User::firstOrCreate([
            'email' => $user->getEmail(),
        ], [
            'name' => str_replace(' ', '_', $user->getName()),
            'password' => Hash::make(Str::random(24)),
            'email_verified_at' => now(),
            'discord_id' => $user->getId(),
            'discord_name' => $user->getNickname(),
        ]);

        Auth::login($user, true);

        return redirect(RouteServiceProvider::HOME);
    }
}
