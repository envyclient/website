<?php

namespace App\Http\Controllers;

use App\Download;
use App\Plan;
use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'forbid-banned-user'])->except('index');
        $this->middleware('admin')->only('admin');
    }

    /**
     * Show the application landing page.
     *
     * @return Renderable
     */
    public function index()
    {
        $plans = Plan::all();
        return view('pages.index')->with([
            'plans' => $plans
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function dashboard()
    {
        $user = auth()->user();
        return view('pages.dashboard.default')->with([
            'user' => $user,
            'configs' => $user->configs(),
            'plans' => Plan::all(),
            'transactions' => $user->wallet->transactions()->orderBy('created_at', 'desc')->get(),
            'nextSubscription' => $user->subscription->end_date->diffInDays()
        ]);
    }

    /**
     * Show the admin dashboard.
     *
     * @return Renderable
     */
    public function admin()
    {
        return view('pages.dashboard.admin')->with([
            'apiToken' => auth()->user()->api_token,
            'downloads' => Download::all()
        ]);
    }
}
