<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_current' => 'required|min:8',
            'password' => 'required|min:8|confirmed|different:password_current',
            'password_confirmation' => 'required_with:password|min:8'
        ]);

        $user = $request->user();
        if (Hash::check($request->password, $user->password)) {
            return back()->with('error', 'You have entered wrong password.');
        }

        $user->fill([
            'password' => Hash::make($request->password)
        ])->save();

        return back()->with('success', 'Your password has been updated.');
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
