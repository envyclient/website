<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CheckStripeSource
{
    public function handle(Request $request, Closure $next)
    {
        if (Cache::has($request->route('stripeSource'))) {
            return $next($request);
        }
        abort(404);
    }
}
