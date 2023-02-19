<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\DiscordHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscordController extends Controller
{
    private string $redirect;

    public function __construct()
    {
        $this->redirect = config('services.discord.redirect');
    }

    public function login()
    {
        return redirect(
            DiscordHelper::getRedirectUrl($this->redirect)
        );
    }

    public function redirect(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect('login');
        }

        try {
            $data = DiscordHelper::getDiscordUser(
                $request->input('code'),
                $this->redirect
            );
        } catch (Exception) {
            return redirect('login');
        }

        // generate a random number
        $rand = random_int(1, 99999);

        // create the user
        $user = User::firstOrCreate([
            'discord_id' => $data['id'],
        ], [
            'name' => "envy_$rand",
            'email' => "envy_$rand@envyclient.com",
            'password' => null,
            'email_verified_at' => now(),
        ]);

        auth()->login($user, true);

        return redirect(route('home'));
    }
}
