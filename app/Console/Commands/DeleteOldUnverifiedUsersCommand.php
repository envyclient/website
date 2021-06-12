<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeleteOldUnverifiedUsersCommand extends Command
{
    protected $signature = 'envy:delete-old-unverified-users';

    public function handle()
    {
        $this->info('Deleting old unverified users...');

        $start = now();

        $count = User::whereNull('email_verified_at')
            ->where('created_at', '<', now()->subDays(10))
            ->delete();

        $this->comment("Deleted $count unverified users.");
        $this->info('Command took: ' . now()->diffInMilliseconds($start) . 'ms');

        return 0;
    }
}
