<?php

namespace App\Http\Controllers;

use App\Events\DiscordAccountConnectedEvent;
use App\Helpers\DiscordHelper;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscordController extends Controller
{
    private string $redirect;

    public function __construct()
    {
        $this->redirect = config('services.discord.redirect.connect');
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
            'code' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return redirect(RouteServiceProvider::HOME)->with('error', 'Connection cancelled.');
        }

        try {
            $data = DiscordHelper::getDiscordUser(
                $request->input('code'),
                $this->redirect
            );
        } catch (Exception) {
            return redirect(RouteServiceProvider::HOME)->with('error', 'Connection cancelled.');
        }

        // check if discord account is already used
        if (DiscordHelper::isAccountAlreadyLinked($data['id'], auth()->id())) {
            return redirect(RouteServiceProvider::HOME)->with('error', 'Discord already linked to another account.');
        }

        // update user account with discord account info
        auth()->user()->update([
            'discord_id' => $data['id'],
        ]);

        // broadcast the discord account connected event
        event(new DiscordAccountConnectedEvent(auth()->user()));

        return redirect(RouteServiceProvider::HOME)->with('success', 'Discord account connected.');
    }
}
