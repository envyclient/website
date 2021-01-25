<?php
return [
    'secret' => env('STRIPE_SECRET'),
    'webhook' => [
        'stripe' => env('STRIPE_WEBHOOK_SECRET'),
        'source' => env('STRIPE_SOURCE_WEBHOOK_SECRET'),
    ],
];
