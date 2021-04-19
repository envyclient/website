<?php

namespace App\Listeners;

use App\Events\DiscordAccountConnected;
use App\Events\SubscriptionCreated;
use App\Events\SubscriptionExpired;
use App\Helpers\Discord;
use App\Models\User;

class UpdateDiscordRole
{
    private array $roles;

    public function __construct()
    {
        $this->roles = [
            'standard' => intval(config('discord.guild.roles.standard')),
            'premium' => intval(config('discord.guild.roles.premium')),
        ];
    }

    public function handleDiscordConnected(DiscordAccountConnected $event)
    {
        $this->handleDiscordRoles($event->user, function (string $discord, string $plan) {
            return "<@$discord> has connected their account, so I updated their role.";
        });
    }

    public function handleSubscriptionCreated(SubscriptionCreated $event)
    {
        $this->handleDiscordRoles($event->user, function (string $discord, string $plan) {
            return "<@$discord> has subscribed to the $plan plan, so I updated their role.";
        });
    }

    public function handleSubscriptionExpired(SubscriptionExpired $event)
    {
        $this->handleDiscordRoles($event->user, function (string $discord, string $plan) {
            return "<@$discord> subscription has expired, so I removed their role.";
        });
    }

    private function handleDiscordRoles(User $user, callable $callback)
    {
        // check if user does not discord linked
        if ($user->discord_id === null) {
            return;
        }

        // user has an active subscription
        if ($user->hasSubscription()) {
            switch ($user->subscription->plan->id) {
                case 1:
                case 3:
                {
                    Discord::updateRole($user->discord_id, $this->roles['premium']);
                    break;
                }
                case 2:
                {
                    Discord::updateRole($user->discord_id, $this->roles['standard']);
                    break;
                }
            }
        } else { // user no longer has an active subscription
            Discord::updateRole($user->discord_id, $this->roles['standard'], true);
            Discord::updateRole($user->discord_id, $this->roles['premium'], true);
        }

        Discord::sendWebhook($callback($user->discord_id, $user->plan?->name));
    }

    public function subscribe($events)
    {
        $events->listen(
            DiscordAccountConnected::class,
            [UpdateDiscordRole::class, 'handleDiscordConnected']
        );

        $events->listen(
            SubscriptionCreated::class,
            [UpdateDiscordRole::class, 'handleSubscriptionCreated']
        );

        $events->listen(
            SubscriptionExpired::class,
            [UpdateDiscordRole::class, 'handleSubscriptionExpired']
        );
    }

}
