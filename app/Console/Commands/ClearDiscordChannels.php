<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ClearDiscordChannels extends Command
{
    const CHANNELS = [
        795476742184632330
    ];

    protected $signature = 'discord:clear';
    protected $description = 'Clears the specified channels.';

    private string $endpoint;
    private string $token;
    private int $guild;

    public function __construct()
    {
        parent::__construct();

        $this->endpoint = config('discord.endpoint');
        $this->token = config('discord.token');
        $this->guild = config('discord.guild.id');
    }

    public function handle()
    {
        foreach (self::CHANNELS as $channel) {
            $messages = Http::withToken(config('discord.token'), 'Bot')
                ->get("https://discord.com/api/channels/$channel/messages?limit=100");

            $response = Http::withToken(config('discord.token'), 'Bot')
                ->withBody(json_encode([
                    'messages' => collect($messages)->pluck('id'),
                ]), 'application/json')
                ->post("https://discord.com/api/channels/$channel/messages/bulk-delete");

            dd($response->status());
        }

        // TODO: send message that channel has been cleared

        return 0;
    }

}
