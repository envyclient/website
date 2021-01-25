<?php

namespace App\Charts;

use App\Models\Invoice;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class SalesChart extends BaseChart
{
    public ?array $middlewares = ['auth', 'admin'];

    public function handler(Request $request): Chartisan
    {
        $chart = Chartisan::build()
            ->labels(['7 days ago', '6 days ago', '5 days ago', '4 days ago', '3 days ago', '2 days ago', 'Yesterday', 'Today']);

        // paypal
        $data = collect();
        for ($days_backwards = 7; $days_backwards >= 0; $days_backwards--) {
            $data->push(
                Invoice::where('method', 'paypal')
                    ->whereDate('created_at', today()->subDays($days_backwards))
                    ->sum('price')
            );
        }
        $chart->dataset('Paypal', $data->toArray());

        // wechat
        $data = collect();
        for ($days_backwards = 7; $days_backwards >= 0; $days_backwards--) {
            $data->push(
                Invoice::where('method', 'wechat')
                    ->whereDate('created_at', today()->subDays($days_backwards))
                    ->sum('price')
            );
        }
        $chart->dataset('WeChat Pay', $data->toArray());

        // crypto
        $data = collect();
        for ($days_backwards = 7; $days_backwards >= 0; $days_backwards--) {
            $data->push(
                Invoice::where('method', 'crypto')
                    ->whereDate('created_at', today()->subDays($days_backwards))
                    ->sum('price')
            );
        }
        $chart->dataset('Crypto', $data->toArray());

        // stripe
        $data = collect();
        for ($days_backwards = 7; $days_backwards >= 0; $days_backwards--) {
            $data->push(
                Invoice::where('method', 'stripe')
                    ->whereDate('created_at', today()->subDays($days_backwards))
                    ->sum('price')
            );
        }
        $chart->dataset('Stripe', $data->toArray());

        return $chart;
    }
}
