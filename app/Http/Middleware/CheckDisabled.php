<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckDisabled
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->disabled) {
            auth()->logout();
            return redirect('/login')->with('error', "Account has been disabled.");
        }
        return $next($request);
    }
}
