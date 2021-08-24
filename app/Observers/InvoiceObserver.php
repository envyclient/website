<?php

namespace App\Observers;

use App\Helpers\Discord;
use App\Models\Invoice;

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
        Discord::sendWebhook($content);
    }
}
