<?php

namespace App\Console\Commands\Discord;

use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncRoles extends Command
{
    protected $signature = 'discord:sync';
    protected $description = 'Sync discord roles with subscriptions.';

    private string $endpoint;
    private string $token;
    private int $guild;
    private array $roles;

    public function __construct()
    {
        parent::__construct();

        $this->endpoint = config('discord.endpoint');
        $this->token = config('discord.token');
        $this->guild = config('discord.guild.id');
        $this->roles = [
            'standard' => intval(config('discord.guild.roles.standard')),
            'premium' => intval(config('discord.guild.roles.premium')),
        ];
    }

    public function handle()
    {
        $start = now();
        $count = 0;

        $subscriptions = Subscription::withTrashed()
            ->with(['user', 'plan'])
            ->get();

        foreach ($subscriptions as $subscription) {
            $user = $subscription->user;

            // check if user does not discord linked
            if ($user->discord_id === null) {
                $this->info("Skipping $user->name due to not having an account linked.");
            }

            if ($subscription->deleted_at === null) { // user no longer has an active subscription
                foreach ($this->roles as $role) {
                    $this->updateRole(intval($user->discord_id), $role, true);
                }
            } else { // user has an active subscription
                switch ($user->subscription->plan->id) {
                    case 1:
                    case 3:
                    {
                        $this->updateRole(
                            intval($user->discord_id),
                            $this->roles['premium'],
                        );
                        break;
                    }
                    case 2:
                    {
                        $this->updateRole(
                            intval($user->discord_id),
                            $this->roles['standard'],
                        );
                        break;
                    }
                }
            }
            $count++;
        }

        $this->info("Synced $count roles.");
        $this->info('Command took: ' . now()->diffInMilliseconds($start) . 'ms');

        return 0;
    }

    private function updateRole(int $userID, int $roleID, bool $remove = false): void
    {
        if ($remove) {
            Http::withToken($this->token, 'Bot')
                ->delete("$this->endpoint/guilds/$this->guild/members/$userID/roles/$roleID");
        } else {
            Http::withToken($this->token, 'Bot')
                ->put("$this->endpoint/guilds/$this->guild/members/$userID/roles/$roleID");
        }
    }
}
