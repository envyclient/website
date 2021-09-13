<?php

namespace App\Console\Commands;

use App\Events\Subscription\SubscriptionExpiredEvent;
use App\Models\Subscription;
use Illuminate\Console\Command;

class DeleteCancelledSubscriptionsCommand extends Command
{
    protected $signature = 'envy:delete-cancelled-subscriptions';

    public function handle(): int
    {
        $this->info('Deleting cancelled subscriptions...');

        $start = now();
        $count = 0;

        $subscriptions = Subscription::with(['user', 'billingAgreement'])
            ->where('end_date', '<=', $start)
            ->get();

        /** @var Subscription $subscription */
        foreach ($subscriptions as $subscription) {
            $count++;

            $user = $subscription->user;

            // user subscribed using PayPal
            if ($subscription->billing_agreement_id !== null) {

                // skip deleting if billing plan is active
                if ($subscription->billingAgreement->state !== Subscription::CANCELED) {
                    continue;
                }

                // delete the subscription and billing agreement
                $user->subscription()->delete();
                $user->billingAgreement()->delete();

                // broadcast subscription expired event
                event(new SubscriptionExpiredEvent($subscription));
            } else if ($subscription->stripe_id !== null) { // user subscribed using stripe

                // skip deleting if stripe subscription is active
                if ($subscription->stripe_status !== Subscription::CANCELED) {
                    continue;
                }

                $user->subscription()->delete();

                // broadcast subscription expired event
                event(new SubscriptionExpiredEvent($subscription));
            } else { // user purchased subscription
                $user->subscription()->delete();

                // broadcast subscription expired event
                event(new SubscriptionExpiredEvent($subscription));
            }
        }

        $this->comment("Processed $count subscriptions.");
        $this->info('Command took: ' . now()->diffInMilliseconds($start) . 'ms');
        return 0;
    }

}
