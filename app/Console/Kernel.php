<?php

namespace App\Console;

use App\Console\Commands\ClearDiscordChannels;
use App\Console\Commands\DeleteCancelledSubscriptions;
use App\Console\Commands\SyncDiscordRoles;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    const EMAILS = [
        'haqgamer66@gmail.com',
    ];

    protected $commands = [
        DeleteCancelledSubscriptions::class,
        SyncDiscordRoles::class,
        ClearDiscordChannels::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('subscriptions:delete')
            ->everyMinute()
            ->emailOutputOnFailure(self::EMAILS);

        $schedule->command('discord:sync')
            ->everyFiveMinutes()
            ->emailOutputOnFailure(self::EMAILS);

        $schedule->command('discord:clear')
            ->cron('0 0 */3 * *')
            ->emailOutputTo(self::EMAILS);

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
