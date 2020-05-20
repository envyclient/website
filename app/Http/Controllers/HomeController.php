<?php

namespace App\Http\Controllers;

use App\Charts\TransactionsChart;
use App\Charts\UsersChart;
use App\Charts\VersionDownloadsChart;
use App\Plan;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except('terms');
        $this->middleware('admin')->only('admin');
    }

    public function index()
    {
        $user = auth()->user();
        return view('pages.index')->with([
            'user' => $user,
            'configs' => $user->configs()->withCount('favorites')->orderBy('updated_at', 'desc')->get(),
            'plans' => Plan::all(),
            'transactions' => $user->wallet->transactions()->orderBy('created_at', 'desc')->get(),
            'nextSubscription' => $user->hasSubscription() ? $user->subscription->end_date->diffInDays() : null
        ]);
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function admin()
    {
        $apiToken = auth()->user()->api_token;

        $usersChart = new UsersChart();
        $usersChart->labels(['7 days ago', '6 days ago', '5 days ago', '4 days ago', '3 days ago', '2 days ago', 'Yesterday', 'Today'])
            ->load(route('api.admin.users.chart') . '?api_token=' . $apiToken);

        $transactionsChart = new TransactionsChart();
        $transactionsChart->labels(['7 days ago', '6 days ago', '5 days ago', '4 days ago', '3 days ago', '2 days ago', 'Yesterday', 'Today'])
            ->load(route('api.admin.transactions.chart') . '?api_token=' . $apiToken);

        $versionsChart = new VersionDownloadsChart();
        $versionsChart->labels(['7 days ago', '6 days ago', '5 days ago', '4 days ago', '3 days ago', '2 days ago', 'Yesterday', 'Today'])
            ->load(route('api.admin.versions.chart') . '?api_token=' . $apiToken);

        return view('pages.admin')->with([
            'apiToken' => $apiToken,
            'usersChart' => $usersChart,
            'transactionsChart' => $transactionsChart,
            'versionsChart' => $versionsChart
        ]);
    }
}
