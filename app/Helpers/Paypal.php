<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Http;

class Paypal
{
    public static function getAccessToken(): string
    {
        $response = Http::withBasicAuth(config('services.paypal.client_id'), config('services.paypal.secret'))
            ->asForm()
            ->post(config('services.paypal.endpoint') . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if ($response->status() !== 200) {
            throw new Exception('Invalid Credentials.');
        }

        return $response->json('access_token');
    }

    public static function cancelBillingAgreement($id, $message): int
    {
        return HTTP::withToken(self::getAccessToken())
            ->withBody(json_encode([
                'note' => $message
            ]), 'application/json')
            ->post(config('services.paypal.endpoint') . "/v1/payments/billing-agreements/$id/cancel")
            ->status();
    }
}
