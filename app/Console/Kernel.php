<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     * comp *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\DeleteCancelledSubscriptions::class,
        \App\Console\Commands\SyncDiscordRoles::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('subscriptions:delete')
            ->everyMinute()
            ->emailOutputOnFailure('haqgamer66@gmail.com');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . ' /Commands');

        require base_path('routes/console.php');
    }
}
