<?php

namespace App\Providers;

use App\Charts\SalesChart;
use App\Charts\UsersChart;
use App\Charts\VersionDownloadsChart;
use App\Models\Invoice;
use App\Models\User;
use App\Observers\InvoiceObserver;
use App\Observers\UserObserver;
use ConsoleTVs\Charts\Registrar as Charts;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Livewire\Component;
use Stripe\Stripe;

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
        Paginator::useBootstrap();

        User::observe(UserObserver::class);
        Invoice::observe(InvoiceObserver::class);

        $charts->register([
            UsersChart::class,
            VersionDownloadsChart::class,
            SalesChart::class,
        ]);

        Stripe::setApiKey(config('stripe.secret'));

        Component::macro('resetFilePond', fn() => $this->dispatchBrowserEvent('filepond-reset'));
    }
}
