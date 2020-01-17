<?php

namespace App\Console;

use App\Notifications\SubscriptionUpdate;
use App\Role;
use App\User;
use App\Util\Constants;
use Carbon\Carbon;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $subscriptions = User::where([
                'end_date' => Carbon::now()->format(Constants::DATE_FORMAT),
                'renew' => true
            ])->get();
            foreach ($subscriptions as $subscription) {
                $subscription->user->renewSubscription();
            }
        })->at('01:00');
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
