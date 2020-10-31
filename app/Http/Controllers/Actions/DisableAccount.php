<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DisableAccount extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function __invoke(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasSubscription()) {
            return back()->with('error', 'You must wait until your subscription has ended before disabling your account.');
        }

        $user->fill([
            'disabled' => true
        ])->save();

        return back()->with('success', 'Account disabled.');
    }
}
