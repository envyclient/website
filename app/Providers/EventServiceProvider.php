<?php

namespace App\Providers;

use App\Events\DiscordAccountConnected;
use App\Events\SubscriptionCreated;
use App\Events\SubscriptionExpired;
use App\Listeners\UpdateDiscordRole;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SocialiteProviders\Discord\DiscordExtendSocialite;
use SocialiteProviders\Manager\SocialiteWasCalled;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SocialiteWasCalled::class => [
            DiscordExtendSocialite::class,
        ],
        SubscriptionCreated::class => [
            UpdateDiscordRole::class,
        ],
        SubscriptionExpired::class => [
            UpdateDiscordRole::class,
        ],
        DiscordAccountConnected::class => [
            UpdateDiscordRole::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
