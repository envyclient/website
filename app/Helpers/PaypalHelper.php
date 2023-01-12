<?php

namespace App\Helpers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaypalHelper
{
    private static function getEndpoint(): string
    {
        return config('services.paypal.endpoint');
    }

    /**
     * Get PayPal API access token.
     *
     * @return string the access token
     *
     * @throws Exception
     */
    private static function getAccessToken(): string
    {
        // send request to get the access token
        $response = Http::withBasicAuth(
            config('services.paypal.client_id'),
            config('services.paypal.secret')
        )
            ->asForm()
            ->post(self::getEndpoint().'/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        // checking if the request was successful
        if ($response->status() !== 200) {
            throw new Exception('Invalid Credentials.');
        }

        // return the access_token
        return $response->json('access_token');
    }

    /**
     * @throws Exception
     */
    public static function verifyWebhook(Request $request): void
    {
        $response = HTTP::withToken(self::getAccessToken())
            ->withBody(json_encode([
                'auth_algo' => $request->header('PAYPAL-AUTH-ALGO'),
                'cert_url' => $request->header('PAYPAL-CERT-URL'),
                'transmission_id' => $request->header('PAYPAL-TRANSMISSION-ID'),
                'transmission_sig' => $request->header('PAYPAL-TRANSMISSION-SIG'),
                'transmission_time' => $request->header('PAYPAL-TRANSMISSION-TIME'),
                'webhook_id' => config('services.paypal.webhook_id'),
                'webhook_event' => $request->json(),
            ]), 'application/json')
            ->post(self::getEndpoint().'/v1/notifications/verify-webhook-signature');

        if ($response->status() !== 200) {
            throw new Exception('Failed PayPal Webhook Signature');
        }
    }

    /**
     * @throws Exception
     */
    public static function createSubscription(User $user, Plan $plan): Response
    {
        $response = Http::withToken(self::getAccessToken())
            ->acceptJson()
            ->withBody(json_encode([
                'plan_id' => $plan->paypal_id,
                'start_time' => now()->addSeconds(30)->toIso8601String(),
                'subscriber' => [
                    'email_address' => $user->email,
                ],
                'application_context' => [
                    'brand_name' => config('app.name'),
                    'locale' => 'en-US',
                    'user_action' => 'SUBSCRIBE_NOW',
                    'payment_method' => [
                        'payer_selected' => 'PAYPAL',
                        'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED',
                    ],
                    'return_url' => route('paypal.success'),
                    'cancel_url' => route('paypal.cancel'),
                ],
            ]), 'application/json')
            ->post(self::getEndpoint().'/v1/billing/subscriptions');

        if ($response->status() !== 201) {
            throw new Exception('Could not create subscription.');
        }

        return $response;
    }

    /**
     * @throws Exception
     */
    public static function cancelSubscription(Subscription $subscription): void
    {
        $response = HTTP::withToken(self::getAccessToken())
            ->withBody(json_encode([
                'note' => 'User cancelled subscription from website.',
            ]), 'application/json')
            ->post(self::getEndpoint().'/v1/billing/subscriptions/'.$subscription->paypal_id.'/cancel');

        if ($response->status() !== 204) {
            throw new Exception('PayPal API request failed.');
        }
    }
}
