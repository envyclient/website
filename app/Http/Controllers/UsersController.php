<?php

namespace App\Http\Controllers;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function disable()
    {
        $user = auth()->user();

        if ($user->hasSubscription()) {
            return back()->with('error', 'You must wait until your subscription has ended before disabling your account.');
        }

        $user->fill([
            'disabled' => true
        ])->save();

        return back()->with('success', 'Account disabled.');
    }
}
