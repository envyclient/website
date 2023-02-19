<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Http\Request;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->hasSubscription()) {
            return $next($request);
        }

        return redirect(route('home'));
    }
}
