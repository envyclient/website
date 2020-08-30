<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CheckDisabled
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws ValidationException
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->disabled) {
            auth()->logout();
            throw ValidationException::withMessages([
                'email' => ['Account has been disabled.'],
            ]);
        }
        return $next($request);
    }
}
