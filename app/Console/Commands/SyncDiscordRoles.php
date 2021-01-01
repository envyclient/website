<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncDiscordRoles extends Command
{
    protected $signature = 'discord:sync';
    protected $description = 'Sync discord roles with subscriptions.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        echo 1;
        return 0;
    }
}
