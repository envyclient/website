<?php

namespace App\Http\Middleware\API;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin
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
        if (auth('api')->check() && auth('api')->user()->admin) {
            return $next($request);
        }
        return response()->json([
            'message' => '401 Unauthorized'
        ], 410);
    }
}
