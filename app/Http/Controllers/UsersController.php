<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['verified', 'auth']);
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

    public function destroy(Request $request, $user)
    {
        $user = User::findOrFail($request->user);
        if ($user->id !== auth()->id() && !auth()->user()->admin) {
            return back()->with('error', 'You do not have the permission to delete this user');
        }

        $user->configs()->delete();
        $user->invoices()->delete();
        $user->subscription()->delete();
        $user->delete();

        return back()->with('success', 'Account deleted');
    }
}
