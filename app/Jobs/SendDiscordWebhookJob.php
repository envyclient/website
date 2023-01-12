<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendDiscordWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 15;

    public function __construct(
        private readonly string $content,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle()
    {
        // send the webhook
        $response = HTTP::timeout(10)
            ->withBody(json_encode([
                'username' => 'Envy Client',
                'avatar_url' => asset('android-chrome-512.png'),
                'tts' => false,
                'content' => $this->content,
            ]), 'application/json')
            ->post(config('services.discord.webhook'));

        // throw an exception if the webhook failed
        if ($response->failed()) {
            throw new Exception('Discord webhook failed.');
        }
    }
}
