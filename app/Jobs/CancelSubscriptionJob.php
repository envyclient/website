<?php

namespace App\Jobs;

use App\Enums\Invoice;
use App\Events\Subscription\SubscriptionCancelledEvent;
use App\Helpers\Paypal;
use App\Models\Subscription;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class CancelSubscriptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 15;

    public function __construct(
        private Subscription $subscription,
        private string       $provider,
    )
    {
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
        if ($this->subscription->status === \App\Enums\Subscription::CANCELED) {
            return;
        }

        if ($this->provider === Invoice::STRIPE) {
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
        // will throw exception if API call fails
        app('stripeClient')->subscriptions->cancel(
            $this->subscription->stripe_id,
            []
        );

        // mark the users' subscription as cancelled on our end
        $this->subscription->update([
            'status' => \App\Enums\Subscription::CANCELED,
        ]);
    }

    /**
     * @throws Exception
     */
    private function handlePayPal()
    {
        // tell PayPal to cancel the users billing agreement
        $response = HTTP::withToken(Paypal::getAccessToken())
            ->withBody(json_encode([
                'note' => 'User cancelled subscription.'
            ]), 'application/json')
            ->post(config('services.paypal.endpoint') . '/v1/billing/subscriptions/' . $this->subscription->paypal_id . '/cancel');

        // user has already cancelled agreement or something went wrong
        if ($response->status() !== 204) {
            throw new Exception('PayPal API request failed.');
        }

        // we don't mark the subscription as cancelled since that is handled in HandlePayPalWebhook.php
    }
}
