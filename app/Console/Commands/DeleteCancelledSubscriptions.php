<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Notifications\SubscriptionUpdated;
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
        $start = now();
        $count = 0;

        $subscriptions = Subscription::where(
            'end_date',
            '<=',
            now()
        )->get();

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

            // send email to user about subscription expired
            $user->notify(new SubscriptionUpdated(
                'Subscription Expired',
                'Your subscription has expired. Please renew it you wish to continue using the client.'
            ));
        }

        $this->info("Deleted $count subscriptions on " . Carbon::now());
        $this->info('Command took: ' . Carbon::now()->diffInMilliseconds($start) . 'ms');

        return 0;
    }
}
