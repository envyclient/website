<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CheckBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     *
     * @throws ValidationException
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->banned) {
            auth()->logout();
            throw ValidationException::withMessages([
                'email' => ['Account has been banned.'],
            ]);
        }

        return $next($request);
    }
}
