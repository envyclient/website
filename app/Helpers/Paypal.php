<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Http;

class Paypal
{
    /**
     * Get PayPal API access token.
     *
     * @return string the access token
     * @throws Exception thrown when invalid credentials
     */
    public static function getAccessToken(): string
    {
        // send request to get
        $response = Http::withBasicAuth(config('services.paypal.client_id'), config('services.paypal.secret'))
            ->asForm()
            ->post(config('services.paypal.endpoint') . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        // checking if the request was successful
        if ($response->status() !== 200) {
            throw new Exception('Invalid Credentials.');
        }

        return $response->json('access_token');
    }
}
