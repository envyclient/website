<?php

namespace App\Listeners;

use App\Events\DiscordAccountConnected;
use App\Events\SubscriptionCreated;
use App\Events\SubscriptionExpired;
use App\Helpers\Discord;

class DiscordRoleSubscriber
{
    public function handleDiscordConnected(DiscordAccountConnected $event)
    {
        Discord::handleDiscordRoles($event->user, function (string $discord, string $plan = null) {
            return "<@$discord> has connected their account, so I synchronized their role.";
        });
    }

    public function handleSubscriptionCreated(SubscriptionCreated $event)
    {
        Discord::handleDiscordRoles($event->user, function (string $discord, string $plan) {
            return "<@$discord> has subscribed to the $plan plan, so I updated their role.";
        });
    }

    public function handleSubscriptionExpired(SubscriptionExpired $event)
    {
        Discord::handleDiscordRoles($event->user, function (string $discord, string $plan = null) {
            return "<@$discord> subscription has expired, so I removed their role.";
        });
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\DiscordAccountConnected',
            [DiscordRoleSubscriber::class, 'handleDiscordConnected']
        );

        $events->listen(
            'App\Events\SubscriptionCreated',
            [DiscordRoleSubscriber::class, 'handleSubscriptionCreated']
        );

        $events->listen(
            'App\Events\SubscriptionExpired',
            [DiscordRoleSubscriber::class, 'handleSubscriptionExpired']
        );
    }
}
