<?php

namespace App\Console\Commands;

use App\Events\UpdateDiscordRole;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\Subscription\SubscriptionUpdated;
use Illuminate\Console\Command;

class DeleteCancelledSubscriptions extends Command
{
    protected $signature = 'envy:delete-cancelled-subscriptions';

    public function handle()
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

            // user subscribed using paypal
            if ($subscription->billing_agreement_id !== null) {
                $billingAgreement = $subscription->billingAgreement;

                // skip deleting if billing plan is active
                if ($billingAgreement->state !== 'Cancelled') {
                    continue;
                }

                // delete the subscription and billing agreement
                $user->subscription()->delete();
                $user->billingAgreement()->delete();
                self::sendNotification($user);
            } else if ($subscription->stripe_id !== null) { // user subscribed using stripe

                // skip deleting if stripe subscription is active
                if ($subscription->stripe_status !== 'Cancelled') {
                    continue;
                }

                $user->subscription()->delete();
                self::sendNotification($user);
            } else { // user purchased subscription
                $user->subscription()->delete();
                self::sendNotification($user);
            }
        }

        $this->comment("Processed $count subscriptions.");
        $this->info('Command took: ' . now()->diffInMilliseconds($start) . 'ms');
        return 0;
    }

    private static function sendNotification(User $user)
    {
        $user->notify(new SubscriptionUpdated(
            'Subscription Expired',
            'Your subscription has expired. Please renew if you wish to continue using the client.',
        ));

        event(new UpdateDiscordRole($user));
    }
}
