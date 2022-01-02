<?php

namespace App\Http\Controllers;

use App\Jobs\SendDiscordWebhookJob;
use App\Models\Invoice;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected static function unauthorized(): JsonResponse
    {
        return response()->json([
            'message' => 'Unauthorized',
        ], 401);
    }

    protected static function bad(): JsonResponse
    {
        return response()->json([
            'message' => '400 Bad Request',
        ], 400);
    }

    protected static function ok(): JsonResponse
    {
        return response()->json([
            'message' => '200 OK',
        ], 200);
    }

    protected static function createInvoice(int $subscription, \App\Enums\Invoice $method, int $price)
    {
        $invoice = Invoice::create([
            'subscription_id' => $subscription,
            'method' => $method,
            'price' => $price,
        ]);

        // building the message
        $content = 'A payment has been received.' . PHP_EOL . PHP_EOL;
        $content = $content . '**User**: ' . $invoice->subscription->user->name . PHP_EOL;
        $content = $content . '**Plan**: ' . $invoice->subscription->plan->name . PHP_EOL;
        $content = $content . '**Method**: ' . $invoice->method . PHP_EOL;
        $content = $content . '**Amount**: ' . $invoice->price;

        // sending the webhook
        SendDiscordWebhookJob::dispatch($content);
    }
}
