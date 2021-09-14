<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class DisableAccount extends Controller
{
    public function __invoke(): RedirectResponse
    {
        $user = auth()->user();

        // if the user has a subscription we redirect them back
        if ($user->hasSubscription()) {
            return back()->with('error', 'You must wait until your subscription has ended before disabling your account.');
        }

        // mark the user as disabled
        $user->update([
            'disabled' => true
        ]);

        // log the user out
        auth()->logout();
        return redirect('/');
    }
}
