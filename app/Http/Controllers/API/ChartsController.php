<?php

namespace App\Http\Controllers\API;

use App\Charts\GameSessionsChart;
use App\Charts\UsersChart;
use App\Charts\VersionDownloadsChart;
use App\Config;
use App\GameSession;
use App\Http\Controllers\Controller;
use App\Subscription;
use App\User;
use App\Version;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Colors\RandomColor;

class ChartsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'api-admin']);
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

    public function onTime()
    {
        return self::getSessionsData();
    }

    public function toggles()
    {
        return self::getSessionsData(true);
    }

    private static function getSessionsData($toggles = false)
    {
        $modules = [];
        $sessions = GameSession::whereDate('updated_at', '>=', Carbon::today()->subDays(7))
            ->where('data', '<>', null)
            ->get();

        foreach ($sessions as $session) {

            // get the on time for each module
            foreach (json_decode($session->data) as $module) {
                $name = $module->name;

                $add = $toggles ? $module->times_toggled : $module->on_time;

                if (isset($modules[$name])) {
                    $time = $modules[$name];
                    $time += $add;
                    $modules[$name] = $time;
                } else {
                    $modules[$name] = $add;
                }
            }
        }

        $max = max(self::getMax($sessions, $toggles), 1);

        $newModules = [];

        // divide all the modules on time by total time
        foreach ($modules as $key => $value) {
            array_push($newModules, ($value / $max) * 100);
        }

        $chart = new GameSessionsChart();
        $chart->dataset('Modules', 'doughnut', $newModules)
            ->backgroundColor(RandomColor::many(30, [
                    'luminosity' => 'light'
                ]
            ));
        return $chart->api();
    }

    private static function getMax($sessions, $toggles = false): int
    {
        $data = 0;
        foreach ($sessions as $session) {
            foreach (json_decode($session->data) as $module) {
                $data += $toggles ? $module->times_toggled : $module->on_time;
            }
        }
        return $data;
    }

    private static function randomColor()
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }
}
