<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AccountCreated;
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
        } catch (Exception) {
            return redirect('login');
        }

        // no email attached to the account
        if (empty($user->getEmail())
            || is_null($user->getEmail())
            || !is_string($user->getEmail())) {
            return redirect('login');
        }

        $userExists = User::where('email', $user->getEmail())
            ->exists();

        $password = Str::random(24);
        $user = User::firstOrCreate([
            'email' => $user->getEmail(),
        ], [
            'name' => 'envy_' . random_int(1, 99999),
            'password' => Hash::make($password),
            'email_verified_at' => now(),
            'discord_id' => $user->getId(),
            'discord_name' => $user->getNickname(),
        ]);

        if (!$userExists) {
            $user->notify(new AccountCreated($password));
        }

        Auth::login($user, true);

        return redirect(RouteServiceProvider::HOME);
    }
}
