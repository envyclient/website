<?php

namespace App\Console;

use App\Console\Commands\DeleteCancelledSubscriptionsCommand;
use App\Console\Commands\DeleteOldMinecraftAccountsCommand;
use App\Console\Commands\DeleteOldUnverifiedUsersCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    const EMAILS = [
        'haqgamer66@gmail.com',
    ];

    protected $commands = [
        DeleteCancelledSubscriptionsCommand::class,
        DeleteOldMinecraftAccountsCommand::class,
        DeleteOldUnverifiedUsersCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('envy:delete-cancelled-subscriptions')
            ->everyMinute()
            ->emailOutputOnFailure(self::EMAILS);

        // delete any old unverified users
        $schedule->command('envy:delete-old-unverified-users')
            ->daily()
            ->emailOutputOnFailure(self::EMAILS);

        // delete any old  minecraft accounts
        $schedule->command('envy:delete-old-minecraft-accounts')
            ->daily()
            ->emailOutputOnFailure(self::EMAILS);

        // clear channels every 3 days
        // $schedule->command('discord:clear')
        //    ->cron('0 0 */3 * *')
        //    ->emailOutputTo(self::EMAILS);

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
