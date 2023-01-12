<?php

namespace App\Http\Middleware\Custom\Setup;

use Closure;
use Illuminate\Http\Request;

class CheckIfPasswordNull
{
    public function handle(Request $request, Closure $next)
    {
        if (! auth()->check()) {
            return $next($request);
        }

        if (is_null(auth()->user()->password)) {
            return redirect()->route('setup-account');
        } else {
            return $next($request);
        }
    }
}
