<?php

namespace App\Providers;

use App\Models\Invoice;
use App\Models\User;
use App\Observers\InvoiceObserver;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Stripe\Stripe;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        JsonResource::withoutWrapping();

        // setting the stripe api key
        Stripe::setApiKey(config('services.stripe.secret'));

        self::registerObservers();
        self::registerMacros();

        // blade @admin
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->admin;
        });

        // default password rules for validation -> Password::defaults()
        Password::defaults(function () {
            $rule = Password::min(8);

            return $this->app->isProduction()
                ? $rule->mixedCase()->uncompromised()
                : $rule;
        });

        // enable n+1 problem check
        Model::preventLazyLoading(!app()->isProduction());
    }

    private static function registerObservers()
    {
        User::observe(UserObserver::class);
        Invoice::observe(InvoiceObserver::class);
    }

    private static function registerMacros()
    {
        Component::macro('resetFilePond', fn() => $this->dispatchBrowserEvent('filepond-reset'));
        Component::macro('resetEasyMDE', fn() => $this->dispatchBrowserEvent('easymde-reset'));
        Component::macro('smallNotify', fn($message) => $this->emitSelf('small-notify', $message));
    }

}
