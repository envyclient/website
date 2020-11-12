<?php

namespace App\Http\Controllers\API;

use App\Charts\UsersChart;
use App\Charts\VersionDownloadsChart;
use App\Models\Config;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Version;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Colors\RandomColor;

class ChartsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'admin']);
    }

    public function users()
    {
        $chart = new UsersChart();

        // users
        $data = [];
        for ($days_backwards = 7; $days_backwards >= 0; $days_backwards--) {
            array_push($data, User::whereDate('created_at', today()->subDays($days_backwards))->count());
        }
        $chart->dataset('Users', 'line', $data)->color('#82c4c3')->fill(false);

        // subscriptions
        $data = [];
        for ($days_backwards = 7; $days_backwards >= 0; $days_backwards--) {
            array_push($data, Subscription::whereDate('created_at', today()->subDays($days_backwards))->count());
        }
        $chart->dataset('Subscriptions', 'line', $data)->color('#50d890')->fill(false);;

        // configs
        $data = [];
        for ($days_backwards = 7; $days_backwards >= 0; $days_backwards--) {
            array_push($data, Config::whereDate('created_at', today()->subDays($days_backwards))->count());
        }
        $chart->dataset('Configs', 'line', $data)->color('#fd5e53')->fill(false);

        return $chart->api();
    }

    public function versions()
    {
        $chart = new VersionDownloadsChart();
        foreach (Version::all() as $version) {
            $data = [];
            for ($days_backwards = 7; $days_backwards >= 0; $days_backwards--) {
                array_push($data, DB::table('user_downloads')
                    ->where('version_id', $version->id)
                    ->whereDate('created_at', today()->subDays($days_backwards))
                    ->count()
                );
            }
            $chart->dataset($version->name, 'bar', $data)->backgroundColor(RandomColor::one([
                    'luminosity' => 'light'
                ]
            ));
        }
        return $chart->api();
    }
}
