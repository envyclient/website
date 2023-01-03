<?php

namespace App\Listeners;

use App\Events\Subscription\SubscriptionCancelledEvent;
use App\Events\Subscription\SubscriptionCreatedEvent;
use App\Events\Subscription\SubscriptionExpiredEvent;
use App\Jobs\SendDiscordWebhookJob;
use App\Notifications\SubscriptionNotification;

class SubscriptionSubscriber
{
    public function subscribe($events): array
    {
        return [
            SubscriptionCreatedEvent::class => 'handleSubscriptionCreated',
            SubscriptionExpiredEvent::class => 'handleSubscriptionExpired',
            SubscriptionCancelledEvent::class => 'handleSubscriptionCancelled',
        ];
    }

    public function handleSubscriptionCreated(SubscriptionCreatedEvent $event)
    {
        $event->user->notify(new SubscriptionNotification(
            'New Subscription',
            'Thank you for subscribing to the ' . $event->subscription->plan->name . ' plan!'
        ));

        // send discord webhook
        $content = 'A subscription has been created.' . PHP_EOL . PHP_EOL;
        $content = $content . '**User**: ' . $event->subscription->user->name . PHP_EOL;
        $content = $content . '**Plan**: ' . $event->subscription->plan->name . PHP_EOL;
        $content = $content . '**Provider**: ' . $event->subscription->paymentProvider->value . PHP_EOL;
        SendDiscordWebhookJob::dispatch($content);
    }

    public function handleSubscriptionExpired(SubscriptionExpiredEvent $event)
    {
        $event->user->notify(new SubscriptionNotification(
            'Subscription Expired',
            'Your subscription has expired. Please renew it if you wish to continue using the client.',
        ));

        // send discord webhook
        $content = 'A subscription has expired.' . PHP_EOL . PHP_EOL;
        $content = $content . '**User**: ' . $event->subscription->user->name . PHP_EOL;
        $content = $content . '**Plan**: ' . $event->subscription->plan->name . PHP_EOL;
        $content = $content . '**Provider**: ' . $event->subscription->paymentProvider->value . PHP_EOL;
        SendDiscordWebhookJob::dispatch($content);
    }

    public function handleSubscriptionCancelled(SubscriptionCancelledEvent $event)
    {
        // send user email about subscription cancellation
        $event->user->notify(new SubscriptionNotification(
            'Subscription Cancelled',
            'Your subscription has been cancelled and you will not be charged at the next billing cycle.'
        ));

        // send discord webhook
        $content = 'A subscription has been cancelled.' . PHP_EOL . PHP_EOL;
        $content = $content . '**User**: ' . $event->subscription->user->name . PHP_EOL;
        $content = $content . '**Plan**: ' . $event->subscription->plan->name . PHP_EOL;
        $content = $content . '**Provider**: ' . $event->subscription->paymentProvider->value . PHP_EOL;
        SendDiscordWebhookJob::dispatch($content);
    }
}
