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
        $this->middleware(['verified', 'auth'])->except('addCredits');
        $this->middleware(['verified', 'auth', 'admin'])->only('addCredits');
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
        if ($code === 403) {
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

    public function addCredits(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|int'
        ]);

        $amount = $request->amount;
        $user->deposit($amount, 'deposit', ['admin_id' => auth()->id(), 'description' => "An admin deposited $amount credits into your account."]);
        return back()->with('success', 'Added credits.');
    }
}
