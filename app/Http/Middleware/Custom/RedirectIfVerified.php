<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Http\Request;

class RedirectIfVerified
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! auth()->check() || is_null(auth()->user()->email_verified_at)) {
            return $next($request);
        } else {
            return redirect(route('home'));
        }
    }
}
