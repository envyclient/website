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
        // prune models
        $schedule->command('model:prune')->daily();

        $schedule->command('envy:delete-cancelled-subscriptions')->everyMinute();

        // delete any old  minecraft accounts
        $schedule->command('envy:delete-old-minecraft-accounts')->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__ . ' /Commands');

        require base_path('routes/console.php');
    }
}
