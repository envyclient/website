<?php

namespace App\Http\Middleware\Custom;

use App\Helpers\PaypalHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VerifyPaypalWebhookSignature
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = HTTP::withToken(PaypalHelper::getAccessToken())
            ->withBody(json_encode([
                'auth_algo' => $request->header('PAYPAL-AUTH-ALGO'),
                'cert_url' => $request->header('PAYPAL-CERT-URL'),
                'transmission_id' => $request->header('PAYPAL-TRANSMISSION-ID'),
                'transmission_sig' => $request->header('PAYPAL-TRANSMISSION-SIG'),
                'transmission_time' => $request->header('PAYPAL-TRANSMISSION-TIME'),
                'webhook_id' => config('services.paypal.webhook_id'),
                'webhook_event' => $request->json()
            ]), 'application/json')
            ->post(config('services.paypal.endpoint') . '/v1/notifications/verify-webhook-signature');

        // webhook signature failed
        if ($response->status() !== 200) {
            return response()->json([
                'message' => 'Failed Webhook Signature',
            ], 400);
        }

        return $next($request);
    }
}
