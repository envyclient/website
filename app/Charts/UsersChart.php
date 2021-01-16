<?php

declare(strict_types=1);

namespace App\Charts;

use App\Models\Config;
use App\Models\Subscription;
use App\Models\User;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class UsersChart extends BaseChart
{
    public ?array $middlewares = ['auth', 'admin'];

    public function handler(Request $request): Chartisan
    {
        $chart = Chartisan::build()
            ->labels(['7 days ago', '6 days ago', '5 days ago', '4 days ago', '3 days ago', '2 days ago', 'Yesterday', 'Today']);

        // users
        $data = collect();
        for ($days_backwards = 7; $days_backwards >= 0; $days_backwards--) {
            $data->push(
                User::whereDate('created_at', today()->subDays($days_backwards))
                    ->count()
            );
        }
        $chart->dataset('Users', $data->toArray());

        // subscriptions
        $data = collect();
        for ($days_backwards = 7; $days_backwards >= 0; $days_backwards--) {
            $data->push(
                Subscription::whereDate('created_at', today()->subDays($days_backwards))
                    ->where('plan_id', '<>', 1)
                    ->count()
            );
        }
        $chart->dataset('Subscriptions', $data->toArray());

        // configs
        $data = collect();
        for ($days_backwards = 7; $days_backwards >= 0; $days_backwards--) {
            $data->push(
                Config::whereDate('created_at', today()->subDays($days_backwards))
                    ->count()
            );
        }
        $chart->dataset('Configs', $data->toArray());

        return $chart;
    }
}
