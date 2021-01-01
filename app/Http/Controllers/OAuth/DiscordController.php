<?php

namespace App\Http\Controllers\OAuth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class DiscordController extends Controller
{
    private string $id;
    private string $secret;
    private string $returnURL;

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);

        $this->id = config('oauth.discord.id');
        $this->secret = config('oauth.discord.secret');
        $this->returnURL = config('oauth.discord.return_url');
    }

    public function login()
    {
        $url = urlencode($this->returnURL);
        return redirect()->away(
            "https://discordapp.com/api/oauth2/authorize?client_id=$this->id&redirect_uri=$url&response_type=code&scope=identify"
        );
    }

    public function callback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect('discord')->with('error', 'Discord connection cancelled.');
        }

        $response = Http::asForm()
            ->acceptJson()
            ->post('https://discordapp.com/api/oauth2/token', [
                'client_id' => $this->id,
                'client_secret' => $this->secret,
                'grant_type' => 'authorization_code',
                'code' => $request->code,
                'redirect_uri' => $this->returnURL,
                'scope' => 'identify',
            ]);

        if ($response->status() !== 200) {
            return redirect('discord')->with('error', 'Unable to link Discord account.');
        }

        $response = Http::withToken(
            $response->json('access_token')
        )->get('https://discordapp.com/api/users/@me');

        // could not get discord account info
        if ($response->status() !== 200) {
            return redirect('discord')->with('error', 'Unable to link Discord account.');
        }

        $user = $request->user();

        // check if discord account is already used
        if (User::where('discord_id', $response->json('id'))->where('id', '<>', $user->id)->exists()) {
            return redirect('discord')->with('error', 'Discord already linked to another account.');
        }

        // update user account with discord account info
        $user->update([
            'discord_id' => $response->json('id'),
            'discord_name' => $response->json('username') . '#' . $response->json('discriminator'),
        ]);

        return redirect('discord')->with('success', 'Discord account connected.');
    }
}
