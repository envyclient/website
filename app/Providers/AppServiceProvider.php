<?php

namespace App\Providers;

use App\Charts\UsersChart;
use App\Charts\VersionDownloadsChart;
use App\Models\User;
use App\Observers\UserObserver;
use ConsoleTVs\Charts\Registrar as Charts;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @param Charts $charts
     * @return void
     */
    public function boot(Charts $charts)
    {
        Schema::defaultStringLength(191);
        JsonResource::withoutWrapping();
        User::observe(UserObserver::class);
        Paginator::useBootstrap();

        $charts->register([
            UsersChart::class,
            VersionDownloadsChart::class,
        ]);
    }
}
