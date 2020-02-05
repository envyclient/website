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
        $this->middleware(['verified', 'auth'])->except('index');
    }

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
        // TODO: do admin check and pass in certain data

        $moneyToday = Transaction::where('type', 'deposit')
            ->whereDate('created_at', Carbon::today()->toString())
            ->sum('amount');

        $moneyWeek = Transaction::where('type', 'deposit')
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('amount');

        $moneyMonth = Transaction::where('type', 'deposit')
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->sum('amount');

        return view('pages.dashboard')->with([
            'user' => auth()->user(),
            'transactions' => auth()->user()->wallet->transactions,
            'plans' => Plan::all(),
            'users' => User::all(),
            'moneyToday' => $moneyToday,
            'moneyWeek' => $moneyWeek,
            'moneyMonth' => $moneyMonth
        ]);
    }
}
