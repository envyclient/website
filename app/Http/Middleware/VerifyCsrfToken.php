<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'paypal/webhook',

        // checkout using stripe (CC & GPay)
        'stripe/checkout',

        // stripe & stripe-source webhook
        'stripe/webhook',

        'discord/webhook',
    ];
}
