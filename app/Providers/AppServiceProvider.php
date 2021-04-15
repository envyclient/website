<?php

namespace App\Providers;

use App\Models\Invoice;
use App\Models\User;
use App\Observers\InvoiceObserver;
use App\Observers\UserObserver;
use Illuminate\Http\Resources\Json\JsonResource;
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
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();

        User::observe(UserObserver::class);
        Invoice::observe(InvoiceObserver::class);

        Stripe::setApiKey(config('stripe.secret'));

        Component::macro('resetFilePond', fn() => $this->dispatchBrowserEvent('filepond-reset'));
        Component::macro('smallNotify', fn($message) => $this->emitSelf('small-notify', $message));
    }
}
