<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\User;
use App\Notifications\Subscription\SubscriptionUpdated;
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

        $subscriptions = Subscription::with(['user', 'billingAgreement'])
            ->where('end_date', '<=', now())
            ->get();

        foreach ($subscriptions as $subscription) {
            $count++;

            $user = $subscription->user;
            $billingAgreement = $subscription->billingAgreement;

            // user is on free plan
            if ($billingAgreement === null) {
                $user->subscription()->delete();
                self::sendNotification($user);
                continue;
            }

            // skip deleting if billing plan is active
            if ($billingAgreement->state !== 'Cancelled') {
                continue;
            }

            // delete the subscription and billing agreement
            $user->subscription()->delete();
            $user->billingAgreement()->delete();

            // send email to user about subscription expired
            self::sendNotification($user);
        }

        $this->info("Deleted $count subscriptions on " . Carbon::now());
        $this->info('Command took: ' . Carbon::now()->diffInMilliseconds($start) . 'ms');

        return 0;
    }

    private static function sendNotification(User $user)
    {
        $user->notify(new SubscriptionUpdated(
            'Subscription Expired',
            'Your subscription has expired. Please renew if you wish to continue using the client.',
        ));
    }
}
