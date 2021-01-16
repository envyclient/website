<?php

namespace App\Charts;

use App\Models\Version;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VersionDownloadsChart extends BaseChart
{
    public ?array $middlewares = ['auth', 'admin'];

    public function handler(Request $request): Chartisan
    {
        $chart = Chartisan::build()
            ->labels(['7 days ago', '6 days ago', '5 days ago', '4 days ago', '3 days ago', '2 days ago', 'Yesterday', 'Today']);

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
            $chart->dataset($version->name, $data->toArray());
        }

        return $chart;
    }
}
