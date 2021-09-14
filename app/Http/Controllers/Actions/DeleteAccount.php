<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Jobs\DeleteAccountJob;
use Illuminate\Http\RedirectResponse;

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

        // dispatch the delete_account_job
        DeleteAccountJob::dispatch($user);

        // log the user out
        auth()->logout();
        return redirect('/');
    }
}
