<?php

namespace App\Http\Controllers;

use App\Plan;
use App\Transaction;
use App\User;
use App\Util\AAL;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['verified', 'auth'])->except('addCredits');
        $this->middleware(['verified', 'auth', 'admin'])->only(['addCredits', 'search']);
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

    public function search(Request $request)
    {
        $request->validate([
            'data' => 'required'
        ]);

        $user = auth()->user();
        $query = $request->data;
        $users = User::where('name', 'LIKE', '%' . $query . '%')->orWhere('email', 'LIKE', '%' . $query . '%')->orWhere('aal_name', 'LIKE', '%' . $query . '%')->get();
        $size = count($users);
        $moneyToday = [];
        $moneyWeek = [];
        $moneyMonth = [];
        $todayTransactions = [];
        $nextSubscription = [];

        if (count($users) === 0) {
            $users = User::all();
            $request->session()->flash('success', null);
            $request->session()->flash('error', "Could not find anything for '$query'.");
            $query = null;
        } else {
            $request->session()->flash('error', null);
            $request->session()->flash('success', "Found $size result(s) matching the query.");
        }

        if ($user->admin) {
            $moneyToday = Transaction::where('type', 'deposit')
                ->whereDate('created_at', Carbon::today())
                ->sum('amount');

            $moneyWeek = Transaction::where('type', 'deposit')
                ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->sum('amount');

            $moneyMonth = Transaction::where('type', 'deposit')
                ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                ->sum('amount');

            $todayTransactions = Transaction::where('type', 'deposit')
                ->whereDate('created_at', Carbon::today())
                ->orderBy('created_at', 'desc')
                ->get();

            $nextSubscription = '∞';
            if ($user->subscription()->exists()) {
                $nextSubscription = $user->subscription->end_date->diffInDays();
            }
        }

        return view('pages.dashboard')->with([
            'user' => $user,
            'transactions' => $user->wallet->transactions()->orderBy('created_at', 'desc')->get(),
            'plans' => Plan::all(),
            'users' => $users,
            'moneyToday' => $moneyToday,
            'moneyWeek' => $moneyWeek,
            'moneyMonth' => $moneyMonth,
            'todayTransactions' => $todayTransactions,
            'nextSubscription' => $nextSubscription,
            'query' => $query
        ]);
    }
}
