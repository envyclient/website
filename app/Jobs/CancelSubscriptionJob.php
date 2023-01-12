<?php

namespace App\Jobs;

use App\Enums\PaymentProvider;
use App\Enums\SubscriptionStatus;
use App\Events\Subscription\SubscriptionCancelledEvent;
use App\Helpers\PaypalHelper;
use App\Models\Subscription;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CancelSubscriptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 15;

    public function __construct(
        private readonly Subscription $subscription,
        private readonly PaymentProvider $provider,
    ) {
        $subscription->update([
            'queued_for_cancellation' => true,
        ]);
    }

    /**
     * @throws \Stripe\Exception\ApiErrorException
     * @throws Exception
     */
    public function handle()
    {
        // checking the subscription has already been cancelled
        if ($this->subscription->status == SubscriptionStatus::CANCELED->value) {
            return;
        }

        if ($this->provider === PaymentProvider::STRIPE) {
            $this->handleStripe();
        } else {
            $this->handlePayPal();
        }

        // mark the subscription as no longer queued for cancellation
        $this->subscription->update([
            'queued_for_cancellation' => false,
        ]);

        // broadcast the subscription cancelled event
        event(new SubscriptionCancelledEvent($this->subscription));
    }

    /**
     * @throws \Stripe\Exception\ApiErrorException
     */
    private function handleStripe()
    {
        // will throw exception if API call fails
        app('stripeClient')->subscriptions->cancel(
            $this->subscription->stripe_id,
            []
        );

        // mark the users' subscription as cancelled on our end
        $this->subscription->update([
            'status' => SubscriptionStatus::CANCELED->value,
        ]);
    }

    /**
     * @throws Exception
     */
    private function handlePayPal()
    {
        // tell PayPal to cancel the users billing agreement
        PaypalHelper::cancelSubscription($this->subscription);

        // we don't mark the subscription as cancelled since that is handled in HandlePayPalWebhook.php
    }
}
