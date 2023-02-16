<?php

namespace App\Http\Middleware\Custom;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;

class CheckNoSubscription
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->hasSubscription()) {
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
