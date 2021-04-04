<?php

namespace App\Console;

use App\Console\Commands\ClearDiscordChannels;
use App\Console\Commands\ClearInactiveAccounts;
use App\Console\Commands\DeleteCancelledSubscriptions;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    const EMAILS = [
        'haqgamer66@gmail.com',
    ];

    protected $commands = [
        DeleteCancelledSubscriptions::class,
        ClearDiscordChannels::class,
        ClearInactiveAccounts::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('subscriptions:delete')
            ->everyMinute()
            ->emailOutputOnFailure(self::EMAILS);

        // clear inactive minecraft accounts
        $schedule->command('minecraft:clear')
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
