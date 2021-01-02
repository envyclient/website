<?php

namespace App\Http\Controllers;

use App\Charts\UsersChart;
use App\Charts\VersionDownloadsChart;
use App\Models\Plan;
use App\Models\Version;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    const CHART_OPTIONS = [
        'tooltip' => [
            'show' => true
        ],
        'scales' => [
            'xAxes' => [
                [
                    'stacked' => true
                ]
            ],
            'yAxes' => [
                [
                    'stacked' => true,
                    'ticks' => [
                        'precision' => 0
                    ]
                ]
            ]
        ]
    ];

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('admin')->only('users', 'versions', 'sessions');
    }

    public function dashboard(Request $request)
    {
        $user = $request->user();
        return view('pages.dashboard.home', [
            'user' => $user,
            'configs' => $user->configs()->withCount('favorites')->orderBy('updated_at', 'desc')->get(),
        ]);
    }

    public function security(Request $request)
    {
        return view('pages.dashboard.security')
            ->with('user', $request->user());
    }

    public function subscriptions(Request $request)
    {
        $user = $request->user();
        return view('pages.dashboard.subscriptions', [
            'user' => $user,
            'plans' => Plan::where('price', '<>', 0)->get(),
            'nextSubscription' => $user->hasSubscription() ? $user->subscription->end_date->diffInDays() : null
        ]);
    }

    public function discord(Request $request)
    {
        return view('pages.dashboard.discord', [
            'user' => $request->user(),
        ]);
    }

    public function users(Request $request)
    {
        $apiToken = $request->user()->api_token;

        // chart
        $chart = new UsersChart();
        $chart->labels(['7 days ago', '6 days ago', '5 days ago', '4 days ago', '3 days ago', '2 days ago', 'Yesterday', 'Today'])
            ->options(self::CHART_OPTIONS)
            ->load(route('api.charts.users') . "?api_token=$apiToken");

        return view('pages.dashboard.admin.users', [
            'apiToken' => $apiToken,
            'chart' => $chart
        ]);
    }

    public function versions(Request $request)
    {
        $apiToken = $request->user()->api_token;

        // chart
        $chart = new VersionDownloadsChart();
        $chart->labels(['7 days ago', '6 days ago', '5 days ago', '4 days ago', '3 days ago', '2 days ago', 'Yesterday', 'Today'])
            ->options(self::CHART_OPTIONS)
            ->load(route('api.charts.versions') . "?api_token=$apiToken");

        return view('pages.dashboard.admin.versions', [
            'apiToken' => $apiToken,
            'chart' => $chart,
        ]);
    }

    public function referrals()
    {
        return view('pages.dashboard.admin.referrals')->with([
        ]);
    }
}
