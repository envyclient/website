<?php

namespace App\Observers;

use App\Models\Invoice;
use Illuminate\Support\Facades\Http;

class InvoiceObserver
{
    public function created(Invoice $invoice)
    {
        // building the message
        $content = 'A payment has been received.' . PHP_EOL . PHP_EOL;
        $content = $content . '**User**: ' . $invoice->user->name . PHP_EOL;
        $content = $content . '**Plan**: ' . $invoice->subscription->plan->name . PHP_EOL;
        $content = $content . '**Method**: ' . $invoice->method . PHP_EOL;
        $content = $content . '**Amount**: ' . $invoice->price;

        // sending the webhook
        HTTP::withBody(json_encode([
            'username' => 'Envy Client',
            'avatar_url' => asset('android-chrome-512.png'),
            'tts' => false,
            'content' => $content,
        ]), 'application/json')
            ->post(config('services.discord.webhook'));
    }
}
