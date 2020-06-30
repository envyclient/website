<?php

namespace App\Console;

use App\Notifications\Generic;
use App\Subscription;
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
            $subscriptions = Subscription::where('end_date', '<=', Carbon::now())->get();

            foreach ($subscriptions as $subscription) {

                $user = $subscription->user;
                $billingAgreement = $subscription->billingAgreement;

                // skip deleting if billing plan is active
                if ($billingAgreement->state !== 'Cancelled') {
                    continue;
                }

                // delete the subscription and notify the user
                $user->subscription()->delete();
                $user->billingAgreement()->delete();
                //$this->notify(new Generic($user, 'Your subscription has expired. Please renew it you wish to continue using the client.', 'Subscription'));
            }

        })->everyMinute();
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
