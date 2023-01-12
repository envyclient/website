<?php

namespace App\Console\Commands;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use Illuminate\Console\Command;

class DeleteCancelledSubscriptionsCommand extends Command
{
    protected $signature = 'envy:delete-cancelled-subscriptions';

    public function handle(): int
    {
        $this->info('Deleting cancelled subscriptions...');

        $start = now();

        // delete all cancelled & past end_date subscriptions
        Subscription::with('user')
            ->where('status', SubscriptionStatus::CANCELED->value)
            ->where('end_date', '<', $start)
            ->each(fn (Subscription $subscription) => $subscription->delete());

        $this->info('Command took: '.now()->diffInMilliseconds($start).'ms');

        return 0;
    }
}
