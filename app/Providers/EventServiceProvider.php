<?php

namespace App\Providers;

use App\Events\ReceivedWebhookEvent;
use App\Listeners\SubscriptionSubscriber;
use App\Listeners\WebhookEventListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ReceivedWebhookEvent::class => [
            WebhookEventListener::class,
        ],
    ];

    protected $subscribe = [
        SubscriptionSubscriber::class,
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
