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
        $data = collect();
        for ($days_backwards = 7; $days_backwards >= 0; $days_backwards--) {
            $data->push(
                User::whereDate('created_at', today()->subDays($days_backwards))->count()
            );
        }
        $chart->dataset('Users', 'bar', $data)
            ->backgroundColor('#82c4c3')
            ->fill(false);

        // subscriptions
        $data = collect();
        for ($days_backwards = 7; $days_backwards >= 0; $days_backwards--) {
            $data->push(
                Subscription::whereDate('created_at', today()->subDays($days_backwards))
                    ->where('billing_agreement_id', '<>', null)
                    ->count()
            );
        }
        $chart->dataset('Subscriptions', 'bar', $data)
            ->backgroundColor('#50d890');

        // configs
        $data = collect();
        for ($days_backwards = 7; $days_backwards >= 0; $days_backwards--) {
            $data->push(
                Config::whereDate('created_at', today()->subDays($days_backwards))->count()
            );
        }
        $chart->dataset('Configs', 'bar', $data)
            ->backgroundColor('#fd5e53');

        return $chart->api();
    }

    public function versions()
    {
        $chart = new VersionDownloadsChart();
        foreach (Version::all() as $version) {
            $data = collect();
            for ($days_backwards = 7; $days_backwards >= 0; $days_backwards--) {
                $data->push(
                    DB::table('user_downloads')
                        ->where('version_id', $version->id)
                        ->whereDate('created_at', today()->subDays($days_backwards))
                        ->count()
                );
            }
            $chart->dataset($version->name, 'bar', $data)
                ->backgroundColor(RandomColor::one([
                        'luminosity' => 'light'
                    ]
                ));
        }
        return $chart->api();
    }
}
