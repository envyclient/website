<?php

namespace App\Console\Commands;

use App\Notifications\Generic;
use App\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteCancelledSubscriptions extends Command
{
    protected $signature = 'subscriptions:delete';
    protected $description = 'Delete all cancelled subscriptions.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $start = Carbon::now();
        $count = 0;

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
            $count++;
            $user->notify(new Generic($user, 'Your subscription has expired. Please renew it you wish to continue using the client.', 'Subscription'));
        }

        $this->info("Deleted $count subscriptions on " . Carbon::now());
        $this->info('Command took: ' . Carbon::now()->diffInMilliseconds($start) . 'ms');
    }
}
