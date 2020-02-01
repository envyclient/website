<?php

namespace App\Http\Controllers;

use App\Plan;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['verified', 'auth']);
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function dashboard()
    {
        return view('home')->with([
            'transactions' => auth()->user()->wallet->transactions,
            'plans' => Plan::all()
        ]);
    }

    public function updateAalName(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string'
        ]);

        $user = auth()->user();

        if ($user->aal_name !== null) {
            return back()->with('error', 'Your AAL name has already been set');
        }

        $user->aal_name = $request->name;
        $user->save();

        return back()->with('success', 'Your AAL name has been set');
    }
}
