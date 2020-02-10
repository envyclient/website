<?php

namespace App\Http\Controllers;

use App\Plan;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['verified', 'auth', 'forbid-banned-user'])->except('index');
        $this->middleware('admin')->only('admin');
    }

    /**
     * Show the application landing page.
     *
     * @return Renderable
     */
    public function index()
    {
        return view('pages.index');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function dashboard()
    {
        $user = auth()->user();

        $nextSubscription = '∞';
        if ($user->subscription()->exists()) {
            $nextSubscription = $user->subscription->end_date->diffInDays();
        }

        return view('pages.dashboard')->with([
            'user' => $user,
            'transactions' => $user->wallet->transactions()->orderBy('created_at', 'desc')->get(),
            'plans' => Plan::all(),
            'nextSubscription' => $nextSubscription,
            'configs' => $user->configs
        ]);
    }

    /**
     * Show the admin dashboard.
     *
     * @return Renderable
     */
    public function admin()
    {
        $user = auth()->user();

        $stats = [];
        $stats['moneyToday'] = Transaction::where('type', 'deposit')
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        $stats['moneyWeek'] = Transaction::where('type', 'deposit')
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('amount');

        $stats['moneyMonth'] = Transaction::where('type', 'deposit')
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->sum('amount');

        $stats['todayTransactions'] = Transaction::where('type', 'deposit')
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.admin')->with([
            'user' => $user,
            'users' => User::all(),
            'stats' => $stats
        ]);
    }
}
