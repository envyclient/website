<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class DeleteAccount extends Controller
{
    public function __invoke(): RedirectResponse
    {
        $user = auth()->user();

        // if the user has a subscription we redirect them back
        if ($user->hasSubscription()) {
            return back()->with('error', 'You cannot delete your account if you have an active subscription.');
        }

        // if the user has ever had a subscription before
        if ($user->subscription()->withTrashed()->exists()) {
            return back()->with('error', 'You cannot delete your account if you have had a subscription.');
        }

        // delete license_requests
        $user->licenseRequests()->delete();

        // delete password_resets
        DB::table('password_resets')->where('email', $user->email)->delete();

        // delete the user
        $user->delete();

        // log the user out
        auth()->logout();
        return redirect('/');
    }
}
