<?php

namespace App\Listeners;

use App\Events\DiscordAccountConnectedEvent;
use App\Events\SubscriptionCreatedEvent;
use App\Events\SubscriptionExpiredEvent;
use App\Helpers\Discord;

class DiscordRoleSubscriber
{
    public function handleDiscordConnected(DiscordAccountConnectedEvent $event)
    {
        Discord::handleDiscordRoles($event->user, function (string $discord, string $plan = null) {
            return "<@$discord> has connected their account, so I synchronized their role.";
        });
    }

    public function handleSubscriptionCreated(SubscriptionCreatedEvent $event)
    {
        Discord::handleDiscordRoles($event->user, function (string $discord, string $plan) {
            return "<@$discord> has subscribed to the $plan plan, so I updated their role.";
        });
    }

    public function handleSubscriptionExpired(SubscriptionExpiredEvent $event)
    {
        Discord::handleDiscordRoles($event->user, function (string $discord, string $plan = null) {
            return "<@$discord> subscription has expired, so I removed their role.";
        });
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\DiscordAccountConnectedEvent',
            [DiscordRoleSubscriber::class, 'handleDiscordConnected']
        );

        $events->listen(
            'App\Events\SubscriptionCreatedEvent',
            [DiscordRoleSubscriber::class, 'handleSubscriptionCreated']
        );

        $events->listen(
            'App\Events\SubscriptionExpiredEvent',
            [DiscordRoleSubscriber::class, 'handleSubscriptionExpired']
        );
    }
}
