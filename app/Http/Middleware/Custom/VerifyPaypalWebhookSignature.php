<?php

namespace App\Http\Middleware\Custom;

use App\Helpers\PaypalHelper;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
        try {
            PaypalHelper::verifyWebhook($request);
        } catch (Exception) {
            throw new AccessDeniedHttpException('Failed PayPal Webhook Signature');
        }

        return $next($request);
    }
}
