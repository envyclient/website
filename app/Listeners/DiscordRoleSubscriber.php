<?php

namespace App\Listeners;

use App\Events\DiscordAccountConnectedEvent;
use App\Events\Subscription\SubscriptionCreatedEvent;
use App\Events\Subscription\SubscriptionExpiredEvent;
use App\Helpers\DiscordHelper;

class DiscordRoleSubscriber
{
    public function subscribe($events): array
    {
        return [
            DiscordAccountConnectedEvent::class => 'handleDiscordConnected',
            SubscriptionCreatedEvent::class => 'handleSubscriptionCreated',
            SubscriptionExpiredEvent::class => 'handleSubscriptionExpired',
        ];
    }

    public function handleDiscordConnected(DiscordAccountConnectedEvent $event)
    {
        DiscordHelper::handleDiscordRoles($event->user, function (string $discord, string $plan = null) {
            return "<@$discord> has connected their account, so I synchronized their role.";
        });
    }

    public function handleSubscriptionCreated(SubscriptionCreatedEvent $event)
    {
        DiscordHelper::handleDiscordRoles($event->user, function (string $discord, string $plan) {
            return "<@$discord> has subscribed to the $plan plan, so I updated their role.";
        });
    }

    public function handleSubscriptionExpired(SubscriptionExpiredEvent $event)
    {
        DiscordHelper::handleDiscordRoles($event->user, function (string $discord, string $plan = null) {
            return "<@$discord> subscription has expired, so I removed their role.";
        });
    }
}
