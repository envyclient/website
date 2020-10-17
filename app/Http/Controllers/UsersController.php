<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function disable(Request $request)
    {
        $user =  $request->user();

        if ($user->hasSubscription()) {
            return back()->with('error', 'You must wait until your subscription has ended before disabling your account.');
        }

        $user->fill([
            'disabled' => true
        ])->save();

        return back()->with('success', 'Account disabled.');
    }
}
