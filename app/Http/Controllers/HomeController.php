<?php

namespace App\Http\Controllers;

use App\Charts\GameSessionsChart;
use App\Charts\UsersChart;
use App\Charts\VersionDownloadsChart;
use App\GameSession;
use App\Plan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    const CHART_OPTIONS = [
        'tooltip' => [
            'show' => true
        ],
        'scales' => [
            'yAxes' => [
                ['ticks' => ['precision' => 0]]
            ]
        ]
    ];

    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except('index', 'terms');
        $this->middleware('admin')->only('users', 'versions', 'sessions');
    }

    public function index()
    {
        return view('pages.index');
    }

    public function home(Request $request)
    {
        $user = $request->user();
        return view('pages.dashboard')->with([
            'user' => $user,
            'configs' => $user->configs()->withCount('favorites')->orderBy('updated_at', 'desc')->get(),
        ]);
    }

    public function security(Request $request)
    {
        return view('pages.security')
            ->with('user', $request->user());
    }

    public function subscriptions(Request $request)
    {
        $user = $request->user();
        return view('pages.subscriptions')->with([
            'user' => $user,
            'plans' => Plan::where('price', '<>', 0)->get(),
            'nextSubscription' => $user->hasSubscription() ? $user->subscription->end_date->diffInDays() : null
        ]);
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function users(Request $request)
    {
        $apiToken = $request->user()->api_token;

        // chart
        $chart = new UsersChart();
        $chart->labels(['7 days ago', '6 days ago', '5 days ago', '4 days ago', '3 days ago', '2 days ago', 'Yesterday', 'Today'])
            ->options(self::CHART_OPTIONS)
            ->load(route('api.charts.users') . "?api_token=$apiToken");

        return view('pages.admin.users')->with([
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
            ->options([
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
            ])
            ->load(route('api.charts.versions') . "?api_token=$apiToken");

        return view('pages.admin.versions')->with([
            'apiToken' => $apiToken,
            'chart' => $chart
        ]);
    }

    public function sessions(Request $request)
    {
        $apiToken = $request->user()->api_token;

        // chart options
        $sessionOptions = [
            'maintainAspectRatio' => false,
            'legend' => [
                'position' => 'top',
            ],
            'title' => [
                'display' => true,
                'text' => 'Chart',
            ],
            'animation' => [
                'animateScale' => true,
                'animateRotate' => true,
            ]
        ];

        // chart
        $gameSessionsChart = new GameSessionsChart();
        $gameSessionsChart->labels(self::getAllModulesNames())
            ->options($sessionOptions, true)
            ->load(route('api.charts.sessions.ontime') . "?api_token=$apiToken");

        $gameSessionsToggleChart = new GameSessionsChart();
        $gameSessionsToggleChart->labels(self::getAllModulesNames())
            ->options($sessionOptions, true)
            ->load(route('api.charts.sessions.toggles') . "?api_token=$apiToken");

        return view('pages.admin.sessions')->with([
            'apiToken' => $apiToken,
            'gameSessionsChart' => $gameSessionsChart,
            'gameSessionsToggleChart' => $gameSessionsToggleChart
        ]);
    }


    public static function getAllModulesNames(): array
    {
        $moduleNames = [];
        $session = GameSession::where('data', '<>', null)->latest()->first();

        if ($session == null) {
            return $moduleNames;
        }

        foreach (json_decode($session->data) as $module) {
            array_push($moduleNames, $module->name);
        }
        return $moduleNames;
    }
}
