<?php

namespace App\Listeners;

use App\Helpers\Discord;

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

    public function handle(\App\Events\UpdateDiscordRole $event)
    {
        $user = $event->user;

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
                    Discord::sendWebhook("Gave <@$user->discord_id> the premium role.");
                    break;
                }
                case 2:
                {
                    Discord::updateRole($user->discord_id, $this->roles['standard']);
                    Discord::sendWebhook("Gave <@$user->discord_id> the standard role.");
                    break;
                }
            }
        } else { // user no longer has an active subscription
            Discord::updateRole($user->discord_id, $this->roles['standard'], true);
            Discord::updateRole($user->discord_id, $this->roles['premium'], true);
            Discord::sendWebhook("Removed roles from <@$user->discord_id>.");
        }
    }

}
