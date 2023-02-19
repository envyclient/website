<?php

return [

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'discord' => [
        'webhook' => env('DISCORD_WEBHOOK'),
        'invite' => env('DISCORD_INVITE'),
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'paypal' => [
        'endpoint' => env('PAYPAL_ENDPOINT'),
        'webhook_id' => env('PAYPAL_WEBHOOK_ID'),
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret' => env('PAYPAL_SECRET'),
    ],

    'youtube' => [
        'key' => env('YOUTUBE_KEY'),
    ],

];
