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
        $user = auth()->user();
        if (!$user->isBanned()) {
            return $next($request);
        }

        auth()->logout();
        return redirect()->to('/')->with('error', "You account has been banned for: {$user->ban_reason}");
    }
}
