<?php

namespace App\Console;

use App\Console\Commands\DeleteCancelledSubscriptionsCommand;
use App\Console\Commands\DeleteOldMinecraftAccountsCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        DeleteCancelledSubscriptionsCommand::class,
        DeleteOldMinecraftAccountsCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('envy:delete-cancelled-subscriptions')
            ->everyMinute();

        // prune models
        $schedule->command('model:prune')
            ->daily();

        // delete any old  minecraft accounts
        $schedule->command('envy:delete-old-minecraft-accounts')
            ->daily();

        // backup
        $schedule->command('backup:clean')
            ->daily()
            ->at('01:00');

        $schedule->command('backup:run')
            ->daily()
            ->at('02:00');
    }

    protected function commands()
    {
        $this->load(__DIR__ . ' /Commands');

        require base_path('routes/console.php');
    }
}
