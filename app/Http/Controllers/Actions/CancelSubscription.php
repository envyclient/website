<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Jobs\CancelSubscriptionJob;
use App\Models\Invoice;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CancelSubscription extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $subscription = $request->user()->subscription;

        // users subscription is already in queue for cancellation
        if ($subscription->queued_for_cancellation) {
            return redirect()
                ->route('home.subscription')
                ->with('error', 'Your subscription is already in queue for cancellation.');
        }

        // cancel subscription
        if ($subscription->stripe_id !== null) {
            return self::handleStripeCancellation($subscription);
        } else {
            return self::handlePayPalCancellation($subscription);
        }
    }

    private static function handleStripeCancellation(Subscription $subscription): RedirectResponse
    {
        // check for already cancelled subscription
        if ($subscription->stripe_status === Subscription::CANCELED) {
            return self::alreadyCancelled();
        }

        return self::successful($subscription, Invoice::STRIPE);
    }

    private static function handlePayPalCancellation(Subscription $subscription): RedirectResponse
    {
        // user does not have a reoccurring subscription
        if (!$subscription->user->hasBillingAgreement()) {  // TODO: show cancelled message on sub page
            return redirect()
                ->route('home.subscription')
                ->with('error', 'You do not need to cancel your subscription.');
        }

        // user already cancelled their PayPal billing agreement
        if ($subscription->billingAgreement->state === Subscription::CANCELED) {
            return self::alreadyCancelled();
        }

        return self::successful($subscription, Invoice::PAYPAL);
    }

    private static function successful(Subscription $subscription, string $provider): RedirectResponse
    {
        // mark the subscription as queued for cancellation
        $subscription->update([
            'queued_for_cancellation' => true,
        ]);

        // dispatch the cancel subscription job
        CancelSubscriptionJob::dispatch($subscription, $provider);

        return redirect()
            ->route('home.subscription')
            ->with('success', 'Your subscription has been queued for cancellation.');
    }

    private static function alreadyCancelled(): RedirectResponse
    {
        return redirect()
            ->route('home.subscription')
            ->with('error', 'You have already cancelled your subscription.');
    }
}
