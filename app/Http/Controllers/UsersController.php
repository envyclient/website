<?php

namespace App\Http\Controllers;

use App\User;
use App\Util\AAL;
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

    public function updateAalName(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:users,aal_name'
        ]);

        $user = auth()->user();

        if ($user->aal_name !== null) {
            return back()->with('error', 'Your AAL name has already been set');
        }

        $user->aal_name = $request->name;
        $user->save();

        return back()->with('success', 'Your AAL name has been set');
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

        $code = AAL::removeUser($user);
        if ($code !== 200 && $code !== 404) {
            return back()->with('error', 'An error occurred while deleting your account. Please contact support.');
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

            if ($user->hasSubscription()) {
                $code = AAL::addUser($user);
                switch ($code) {
                    case 409:
                    {
                        return back()->with('error', 'This user already owns the app.');
                        break;
                    }
                    case 404:
                    {
                        return back()->with('error', 'This user has an invalid AAL name.');
                        break;
                    }
                    case 403:
                    {
                        return back()->with('error', 'An error has occurred. Please contact support.');
                        break;
                    }
                    case 400:
                    {
                        return back()->with('error', 'App user limit has been reached.');
                        break;
                    }
                    case 200: // success
                    {
                        break;
                    }
                    default: // any other error codes
                    {
                        return back()->with('error', 'An unexpected error has occurred while adding the user back to the client.');
                        break;
                    }
                }
            }

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

                $code = AAL::removeUser($user);
                if ($code !== 200 && $code !== 404) {
                    return back()->with('error', "Could not ban {$user->name} due to an AAL error.");
                }
            }

            $user->fill([
                'ban_reason' => $request->reason
            ])->save();

            return back()->with('success', "User {$user->name} has been banned.");
        }
    }
}
