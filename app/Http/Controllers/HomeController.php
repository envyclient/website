<?php

namespace App\Http\Controllers;

use App\Plan;
use App\Subscription;
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

        $nextSubscription = '∞';
        if ($user->hasSubscription()) {
            $nextSubscription = $user->subscription->end_date->diffInDays();
        }

        return view('pages.dashboard.default')->with([
            'user' => $user,
            'transactions' => $user->wallet->transactions()->orderBy('created_at', 'desc')->get(),
            'plans' => Plan::all(),
            'nextSubscription' => $nextSubscription
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

        $users = User::all();
        $totalUsers['total'] = $users->count();
        $totalUsers['today'] = $users->filter(function ($value, $key) {
            return $value->created_at == Carbon::today();
        })->count();
        $totalUsers['week'] = $users->filter(function ($value, $key) {
            return $value->created_at >= Carbon::now()->startOfWeek() && $value->created_at <= Carbon::now()->endOfWeek();
        })->count();
        $totalUsers['month'] = $users->filter(function ($value, $key) {
            return $value->created_at >= Carbon::now()->firstOfMonth() && $value->created_at <= Carbon::now()->lastOfMonth();
        })->count();

        $subscriptions = Subscription::all();
        $premiumUsers['total'] = $subscriptions->count();
        $premiumUsers['today'] = $subscriptions->filter(function ($value, $key) {
            return $value->created_at == Carbon::today();
        })->count();
        $premiumUsers['week'] = $subscriptions->filter(function ($value, $key) {
            return $value->created_at >= Carbon::now()->startOfWeek() && $value->created_at <= Carbon::now()->endOfWeek();
        })->count();
        $premiumUsers['month'] = $subscriptions->filter(function ($value, $key) {
            return $value->created_at >= Carbon::now()->firstOfMonth() && $value->created_at <= Carbon::now()->lastOfMonth();
        })->count();

        $latestUser['regular'] = User::latest()->first();
        $latestUser['premium'] = Subscription::latest()->first()->user;

        return view('pages.dashboard.admin')->with([
            'user' => $user,
            'users' => $users,
            'stats' => $stats,
            'totalUsers' => $totalUsers,
            'premiumUsers' => $premiumUsers,
            'latestUser' => $latestUser
        ]);
    }
}
