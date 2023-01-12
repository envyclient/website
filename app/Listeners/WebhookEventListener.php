<?php

namespace App\Listeners;

use App\Events\ReceivedWebhookEvent;
use App\Jobs\SendDiscordWebhookJob;

class WebhookEventListener
{
    public function handle(ReceivedWebhookEvent $event)
    {
        $content = 'A webhook has been received.'.PHP_EOL.PHP_EOL;
        $content = $content.'**Provider**: '.$event->provider->value.PHP_EOL;
        $content = $content.'**Type**: '.$event->type.PHP_EOL;
        SendDiscordWebhookJob::dispatch($content);
    }
}
