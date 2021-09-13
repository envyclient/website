<?php

namespace App\Jobs;

use App\Events\Subscription\SubscriptionCancelledEvent;
use App\Helpers\Paypal;
use App\Models\Invoice;
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

    public int $tries = 0;

    public function __construct(
        private Subscription $subscription,
        private string       $provider,
    )
    {
    }

    /**
     * @throws \Stripe\Exception\ApiErrorException
     * @throws Exception
     */
    public function handle()
    {
        if ($this->provider === Invoice::STRIPE) {
            $this->handleStripe();
        } else {
            $this->handlePayPal();
        }

        // broadcast the subscription cancelled event
        event(new SubscriptionCancelledEvent($this->subscription));

        // mark the subscription as no longer queued for cancellation
        $this->subscription->update([
            'queued_for_cancellation' => false,
        ]);

        // send discord webhook on cancellation
        $content = 'A user has cancelled their subscription.' . PHP_EOL . PHP_EOL;
        $content = $content . '**User**: ' . $this->subscription->user->name . PHP_EOL;
        $content = $content . '**Provider**: ' . $this->provider . PHP_EOL;
        SendDiscordWebhookJob::dispatch($content);
    }

    /**
     * @throws \Stripe\Exception\ApiErrorException
     */
    private function handleStripe()
    {
        // tell stripe to cancel the users subscription
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        // will throw exception if API call fails
        $stripe->subscriptions->cancel(
            $this->subscription->stripe_id,
            []
        );

        // mark the users' subscription as cancelled on our end
        $this->subscription->update([
            'stripe_status' => Subscription::CANCELED,
        ]);
    }

    /**
     * @throws Exception
     */
    private function handlePayPal()
    {
        // tell PayPal to cancel the users billing agreement
        $response = Paypal::cancelBillingAgreement(
            $this->subscription->billingAgreement->billing_agreement_id,
            'User cancelled subscription.'
        );

        // user has already cancelled agreement or something went wrong
        if ($response->failed()) {
            throw new Exception('PayPal API request failed.');
        }

        // we don't mark the subscription as cancelled since that is handled in HandlePayPalWebhook.php
    }

    public function retryUntil()
    {
        return now()->addDay();
    }
}
