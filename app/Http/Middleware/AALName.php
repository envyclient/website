<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AALName
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
        if (auth()->user()->aal_name !== null) {
            return $next($request);
        }
        return back()->with('error', 'You must set your AAL name');
    }
}
