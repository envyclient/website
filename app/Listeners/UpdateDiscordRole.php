<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Http;

class UpdateDiscordRole
{
    private string $endpoint;
    private string $token;
    private string $guild;
    private array $roles;

    public function __construct()
    {
        $this->endpoint = config('discord.endpoint');
        $this->token = config('discord.token');
        $this->guild = config('discord.guild.id');
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
        if ($user->subscription->deleted_at === null) {
            switch ($user->subscription->plan->id) {
                case 1:
                case 3:
                {
                    $this->updateRole($user->discord_id, $this->roles['premium']);
                    break;
                }
                case 2:
                {
                    $this->updateRole($user->discord_id, $this->roles['standard']);
                    break;
                }
            }
        } else { // user no longer has an active subscription
            $this->updateRole($user->discord_id, $this->roles['standard'], true);
            $this->updateRole($user->discord_id, $this->roles['premium'], true);
        }
    }

    private function updateRole(int $userID, int $roleID, bool $remove = false): void
    {
        if ($remove) {
            $response = Http::withToken($this->token, 'Bot')
                ->delete("$this->endpoint/guilds/$this->guild/members/$userID/roles/$roleID");

        } else {
            $response = Http::withToken($this->token, 'Bot')
                ->put("$this->endpoint/guilds/$this->guild/members/$userID/roles/$roleID");
        }

        // rate limit
        /*if ($response->header('X-RateLimit-Remaining') == 0) {
            $sleep = $response->header('X-RateLimit-Reset-After');
            sleep(intval($sleep));
        }*/
    }
}
