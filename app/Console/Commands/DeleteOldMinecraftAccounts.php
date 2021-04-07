<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeleteOldMinecraftAccounts extends Command
{
    protected $signature = 'envy:delete-old-minecraft-accounts';

    public function handle()
    {
        $this->info('Deleting old minecraft accounts...');

        $start = now();

        $count = User::whereNotNull('current_account')
            ->whereDate('updated_at', '<=', today()->subDays(3))
            ->update([
                'current_account' => null,
            ]);
        
        $this->comment("Deleted $count minecraft accounts.");
        $this->info('Command took: ' . now()->diffInMilliseconds($start) . 'ms');

        return 0;
    }
}
