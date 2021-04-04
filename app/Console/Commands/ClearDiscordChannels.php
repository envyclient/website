<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ClearDiscordChannels extends Command
{
    const CHANNELS = [
        794374280024031277,
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

            $this->info("Checking Channel=$channel.");

            while (true) {
                $response = Http::withToken($this->token, 'Bot')
                    ->get("$this->endpoint/channels/$channel/messages?limit=100");

                // checking if were able to
                if ($response->status() !== 200) {
                    $this->error('Unable to fetch messages for channel.');
                    break;
                }

                // checking if channel has no message
                $messages = collect($response->json());
                if ($messages->count() <= 0) {
                    break;
                }

                $response = Http::withToken($this->token, 'Bot')
                    ->withBody(json_encode([
                        'messages' => $messages->pluck('id'),
                    ]), 'application/json')
                    ->post("$this->endpoint/channels/$channel/messages/bulk-delete");


                // checking if we were able to delete the messages
                if ($response->status() !== 204) {
                    $this->error('Unable to delete messages for channel.');
                    break;
                }

                $this->info("Chanel=$channel, Deleted={$messages->count()}.");
            }
        }

        return 0;
    }

}
