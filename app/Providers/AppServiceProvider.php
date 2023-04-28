<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Livewire\Component;
use Stripe\Stripe;
use Stripe\StripeClient;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        JsonResource::withoutWrapping();

        // setting the stripe api key
        Stripe::setApiKey(
            config('services.stripe.secret')
        );

        // create StripeClient singleton
        $this->app->singleton('stripeClient', function () {
            return new StripeClient(
                config('services.stripe.secret')
            );
        });

        // register macros
        Component::macro('resetFilePond', fn() => $this->dispatchBrowserEvent('filepond-reset'));
        Component::macro('resetEasyMDE', fn() => $this->dispatchBrowserEvent('easymde-reset'));
        Component::macro('smallNotify', fn($message) => $this->emitSelf('small-notify', $message));

        // blade @admin
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->admin;
        });

        // enable n+1 problem check
        Model::preventLazyLoading(
            !app()->isProduction()
        );

        // forcing https scheme
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }
    }
}
