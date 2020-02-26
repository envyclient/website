<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['verified', 'auth', 'forbid-banned-user']);
        $this->middleware('admin')->only(['addCredits', 'ban', 'search']);
    }

    public function updateCape(Request $request)
    {
        $request->validate([
            'file' => 'required|file|image|mimes:png|max:280'
        ]);

        $user = auth()->user();

        $file = $request->file('file');
        $fileName = md5($user->id) . '.' . $file->getClientOriginalExtension();
        Storage::putFileAs(User::CAPES_DIRECTORY, $file, $fileName);

        $user->cape = $fileName;
        $user->save();

        return back()->with('success', 'Cape updated');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_current' => 'required|min:8',
            'password' => 'required|min:8|confirmed|different:password_current',
            'password_confirmation' => 'required_with:password|min:8'
        ]);

        $user = auth()->user();
        if (Hash::check($request->password, $user->password)) {
            return back()->with('error', 'You have entered wrong password');
        }

        $user->fill([
            'password' => Hash::make($request->password)
        ])->save();

        return back()->with('success', 'Your password has been updated');
    }

    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->id !== auth()->id() && !auth()->user()->admin) {
            return back()->with('error', 'You do not have the permission to delete this user');
        }

        $user->configs()->delete();
        $user->invoices()->delete();
        $user->subscription()->delete();
        $user->transactions()->delete();
        $user->wallet()->delete();
        $user->delete();

        return back()->with('success', 'Account deleted');
    }

    ///////////// admin

    public function addCredits(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $admin = auth()->user();
        $request->validate([
            'amount' => 'required|int'
        ]);

        $amount = $request->amount;
        $user->deposit($amount, 'deposit', ['admin_id' => auth()->id(), 'description' => "Admin {$admin->name} deposited $amount credits into your account."]);
        return back()->with('success', "Added $amount credits to {$user->name}'s account.");
    }

    public function ban(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->isBanned()) { // this user is currently banned so we unban him
                        $user->fill([
                'ban_reason' => null
            ])->save();

            return back()->with('success', "User {$user->name} has been unbanned.");
        } else { // not banned so we ban the user

            $request->validate([
                'reason' => 'required|string'
            ]);

            if ($user->hasSubscription()) {
                $user->subscription->fill([
                    'renew' => false
                ])->save();
            }

            $user->fill([
                'ban_reason' => $request->reason
            ])->save();

            return back()->with('success', "User {$user->name} has been banned.");
        }
    }
}
