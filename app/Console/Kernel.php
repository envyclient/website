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
            $subscriptions = Subscription::where([
                ['renew', '=', true],
                ['end_date', '<=', Carbon::now()]
            ])->get();
            foreach ($subscriptions as $subscription) {

                $user = $subscription->user;
                $plan = $user->subscription->plan;

                if ($user->canWithdraw($plan->price) && $user->subscription->renew) {
                    $user->subscription->end_date = Carbon::now()->addDays($user->subscription->plan->interval);
                    $user->subscription->save();

                    $user->withdraw($plan->price, 'withdraw', ['plan_id' => $plan->id, 'description' => "Renewal of plan {$plan->title}."]);
                    $this->notify(new Generic($user, 'Your subscription has been renewed.', 'Subscription'));
                    return true;
                } else {
                    $user->subscription()->delete();
                    $this->notify(new Generic($user, 'Your subscription has failed to renew due to lack of credits. Please renew it you wish to continue using the client.', 'Subscription'));
                    return false;
                }
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
