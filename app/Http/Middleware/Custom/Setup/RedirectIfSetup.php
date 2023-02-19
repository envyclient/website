<?php

namespace App\Http\Middleware\Custom\Setup;

use Closure;
use Illuminate\Http\Request;

class RedirectIfSetup
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! auth()->check() || is_null(auth()->user()->password)) {
            return $next($request);
        } else {
            return redirect(route('home'));
        }
    }
}
