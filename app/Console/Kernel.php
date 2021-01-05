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
        'matko.vukovic.csgo1@gmail.com',
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

        /*  $schedule->command('discord:clear')
              ->daily()
              ->emailOutputOnFailure(self::EMAILS);*/
    }

    protected function commands()
    {
        $this->load(__DIR__ . ' /Commands');

        require base_path('routes/console.php');
    }
}
