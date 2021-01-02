<?php

namespace App\Console\Commands;

use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use RestCord\DiscordClient;

class SyncDiscordRoles extends Command
{
    protected $signature = 'discord:sync';
    protected $description = 'Sync discord roles with subscriptions.';

    private $discord;
    private int $guild;
    private array $roles;

    public function __construct()
    {
        parent::__construct();

        $this->discord = new DiscordClient([
            'token' => config('discord.token'),
        ]);

        $this->guild = config('discord.guild');
        $this->roles = [
            'standard' => intval(config('discord.roles.standard')),
            'premium' => intval(config('discord.roles.premium')),
        ];
    }

    public function handle()
    {
        $start = now();
        $count = 0;

        $users = User::with('subscription.plan')
            ->where('discord_id', '<>', null)
            ->get();

        foreach ($users as $user) {

            if ($user->subscription !== null) {
                switch ($user->subscription->plan->id) {
                    case 1:
                    case 3:
                    {
                        $this->discord->guild->addGuildMemberRole([
                            'guild.id' => $this->guild,
                            'user.id' => intval($user->discord_id),
                            'role.id' => $this->roles['premium'],
                        ]);
                        break;
                    }
                    case 2:
                    {
                        $this->discord->guild->addGuildMemberRole([
                            'guild.id' => $this->guild,
                            'user.id' => intval($user->discord_id),
                            'role.id' => $this->roles['standard'],
                        ]);
                        break;
                    }
                }
            } else {
                $this->removeRole(
                    intval($user->discord_id),
                    $this->roles['standard'],
                );

                $this->removeRole(
                    intval($user->discord_id),
                    $this->roles['premium'],
                );
            }

            $count++;
        }

        $this->info("Synced $count roles.");
        $this->info('Command took: ' . now()->diffInMilliseconds($start) . 'ms');

        return 0;
    }

    private function removeRole(int $userID, int $roleID): void
    {
        try {
            $this->discord->guild->removeGuildMemberRole([
                'guild.id' => $this->guild,
                'user.id' => $userID,
                'role.id' => $roleID,
            ]);
        } catch (Exception $e) {
            // user not in envy discord
        }
    }
}
