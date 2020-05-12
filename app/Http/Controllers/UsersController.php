<?php

namespace App\Http\Controllers;

use App\User;
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

    public function disable(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->id !== $request->user()->id()) {
            return back()->with('error', 'You do not have the permission to disable this user.');
        }

        $user->fill([
            'disabled' => true
        ])->save();

        return back()->with('success', 'Account disabled.');
    }
}
