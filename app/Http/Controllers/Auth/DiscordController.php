<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Strng;
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

        $userExists = User::where('email', $user->getEmail())->exists();

        $password = Str::random(24);
        $user = User::firstOrCreate([
            'email' => $user->getEmail(),
        ], [
            'name' => Strng::clean($user->getName()),
            'password' => Hash::make($password),
            'email_verified_at' => now(),
            'discord_id' => $user->getId(),
            'discord_name' => $user->getNickname(),
        ]);

        if (!$userExists) {
            $user->notify(new AccountCreated($password));
        }

        // check if name is too short
        if (strlen($user->name) < 3) {
            $user->update([
                'name' => "Envy_$user->id"
            ]);
        }

        Auth::login($user, true);

        return redirect(RouteServiceProvider::HOME);
    }
}
