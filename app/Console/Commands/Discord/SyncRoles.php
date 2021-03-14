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
    private string $guild;
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

        // arrays to hold the discord ids
        $standard = collect();
        $premium = collect();
        $remove = collect();

        $subscriptions = Subscription::withTrashed()
            ->with(['user', 'plan'])
            ->orderBy('created_at')
            ->get();

        foreach ($subscriptions as $subscription) {
            $user = $subscription->user;

            // check if user does not discord linked
            if ($user->discord_id === null) {
                $this->info("Skipping $user->name due to not having an account linked.");
                continue;
            }

            // user has an active subscription
            if ($subscription->deleted_at === null) {
                switch ($user->subscription->plan->id) {
                    case 1:
                    case 3:
                    {
                        $premium->push(intval($user->discord_id));
                        break;
                    }
                    case 2:
                    {
                        $standard->push(intval($user->discord_id));
                        break;
                    }
                }
            } else { // user no longer has an active subscription
                $remove->push(intval($user->discord_id));
            }
        }

        foreach ($remove->unique() as $id) {
            $this->info("Removing roles from $id.");
            $this->updateRole($id, $this->roles['standard'], true);
            $this->updateRole($id, $this->roles['premium'], true);
        }
        foreach ($standard->unique() as $id) {
            $this->info("Giving standard role to $id.");
            $this->updateRole($id, $this->roles['standard']);
        }
        foreach ($premium->unique() as $id) {
            $this->info("Giving premium role to $id.");
            $this->updateRole($id, $this->roles['premium']);
        }

        $this->info('Command took: ' . now()->diffInMilliseconds($start) . 'ms');

        return 0;
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
        if ($response->header('X-RateLimit-Remaining') == 0) {
            $sleep = $response->header('X-RateLimit-Reset-After');
            $this->info("Hit rate limit, sleeping for $sleep seconds.");
            sleep(intval($sleep));
        }
    }
}
