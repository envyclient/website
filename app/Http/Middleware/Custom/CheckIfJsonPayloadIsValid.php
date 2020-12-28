<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Http\Request;

class CheckIfJsonPayloadIsValid
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
        if (empty($request->json()->all())) {
            return response()->json([
                'message' => 'The request is not a valid JSON.',
            ], 400);
        }

        return $next($request);
    }
}
