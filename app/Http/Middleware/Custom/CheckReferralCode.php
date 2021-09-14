<?php

namespace App\Http\Middleware\Custom;

use App\Models\ReferralCode;
use Closure;
use Illuminate\Http\Request;

class CheckReferralCode
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('ref') && ReferralCode::where('code', $request->query('ref'))->exists()) {
            return redirect($request->url())
                ->withCookie(
                    cookie()->forever('referral', $request->query('ref'))
                );
        }
        return $next($request);
    }
}
