<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncDiscordRoles extends Command
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

        $users = User::has('subscription')
            ->with('subscription.plan')
            ->where('discord_id', '<>', null)
            ->get();

        foreach ($users as $user) {

            if ($user->subscription !== null) {
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
            } else {
                // removing the 2 roles
                foreach ($this->roles as $role) {
                    $this->updateRole(intval($user->discord_id), $role, true);
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
                ->put("$this->endpoint/guilds/$this->guild/members/$userID/roles/$roleID");
        } else {
            Http::withToken($this->token, 'Bot')
                ->delete("$this->endpoint/guilds/$this->guild/members/$userID/roles/$roleID");
        }
    }
}
