<?php

namespace App\Http\Middleware\Custom\Setup;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;

class RedirectIfSetup
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
        if (!auth()->check()) {
            return $next($request);
        }

        if (!is_null(auth()->user()->password)) {
            return redirect(RouteServiceProvider::HOME);
        } else {
            return $next($request);
        }
    }
}
