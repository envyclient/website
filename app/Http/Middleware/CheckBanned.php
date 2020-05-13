<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckBanned
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
        if (auth()->check() && auth()->user()->isBanned()) {
            $user = auth()->user();
            auth()->logout();
            return redirect('/login')->with('error', "Account has been banned for: {$user->ban_reason}");
        }
        return $next($request);
    }
}
