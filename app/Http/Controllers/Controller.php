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

    public static function bad(): JsonResponse
    {
        return response()->json([
            'message' => '400 Bad Request',
        ], 400);
    }

    public static function ok(): JsonResponse
    {
        return response()->json([
            'message' => '200 OK',
        ]);
    }

    protected static function createInvoice(int $user, int $subscription, string $method, int $price)
    {
        $invoice = Invoice::create([
            'user_id' => $user,
            'subscription_id' => $subscription,
            'method' => $method,
            'price' => $price,
        ]);

        // building the message
        $content = 'A payment has been received.' . PHP_EOL . PHP_EOL;
        $content = $content . '**User**: ' . $invoice->user->name . PHP_EOL;
        $content = $content . '**Plan**: ' . $invoice->subscription->plan->name . PHP_EOL;
        $content = $content . '**Method**: ' . $invoice->method . PHP_EOL;
        $content = $content . '**Amount**: ' . $invoice->price;

        // sending the webhook
        SendDiscordWebhookJob::dispatch($content);
    }
}
