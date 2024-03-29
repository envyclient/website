<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Http\Request;
use Stripe\Exception\SignatureVerificationException;
use Stripe\WebhookSignature;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class VerifyStripeWebhookSignature
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            WebhookSignature::verifyHeader(
                $request->getContent(),
                $request->header('stripe-signature'),
                config('services.stripe.webhook_secret'),
            );
        } catch (SignatureVerificationException $exception) {
            throw new AccessDeniedHttpException($exception->getMessage(), $exception);
        }

        return $next($request);
    }
}
