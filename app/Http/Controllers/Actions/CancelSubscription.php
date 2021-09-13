<?php

namespace App\Http\Controllers\Actions;

use App\Events\Subscription\SubscriptionCancelledEvent;
use App\Helpers\Paypal;
use App\Http\Controllers\Controller;
use App\Jobs\SendDiscordWebhookJob;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CancelSubscription extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        // cancel stripe subscription
        if ($user->subscription->stripe_id !== null) {

            // tell stripe to cancel the users subscription
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $stripe->subscriptions->cancel(
                $user->subscription->stripe_id,
                []
            );

            // mark the users' subscription as cancelled on our end
            $user->subscription->update([
                'stripe_status' => Subscription::CANCELED,
            ]);

            // broadcast the subscription cancelled event
            event(new SubscriptionCancelledEvent($user->subscription));

            return self::successful($user->name, 'Stripe');
        }

        // user does not have a reoccurring subscription
        if (!$user->hasBillingAgreement()) {
            return redirect()
                ->route('home.subscription')
                ->with('error', 'You do not need to cancel your subscription.');
        }

        // user already cancelled their PayPal billing agreement
        if ($user->isBillingAgreementCancelled()) {
            return redirect()
                ->route('home.subscription')
                ->with('error', 'You have already cancelled your subscription.');
        }

        // tell PayPal to cancel the users billing agreement
        $response = Paypal::cancelBillingAgreement(
            $user->billingAgreement->billing_agreement_id,
            'User cancelled subscription.'
        );

        // user has already cancelled agreement or something went wrong
        if ($response->failed()) {
            return redirect()
                ->route('home.subscription')
                ->with('error', 'You have already cancelled your subscription.');
        }

        // broadcast the subscription cancelled event
        event(new SubscriptionCancelledEvent($user->subscription));

        return self::successful($user->name, 'PayPal');
    }

    private static function successful(string $user, string $provider): RedirectResponse
    {
        $content = 'A user has cancelled their subscription.' . PHP_EOL . PHP_EOL;
        $content = $content . '**User**: ' . $user . PHP_EOL;
        $content = $content . '**Provider**: ' . $provider . PHP_EOL;
        SendDiscordWebhookJob::dispatch($content);

        return redirect()
            ->route('home.subscription')
            ->with('success', 'Your subscription has been queued for cancellation.');

    }
}
