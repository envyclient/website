<?php

namespace App\Http\Controllers\Actions;

use App\Enums\PaymentProvider;
use App\Http\Controllers\Controller;
use App\Jobs\CancelSubscriptionJob;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CancelSubscription extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $subscription = $request->user()->subscription;

        // check for already cancelled subscription
        if ($subscription->status === \App\Enums\Subscription::CANCELED->value) {
            return redirect()
                ->route('home.subscription')
                ->with('error', 'You have already cancelled your subscription.');
        }

        // users subscription is already in queue for cancellation
        if ($subscription->queued_for_cancellation) {
            return redirect()
                ->route('home.subscription')
                ->with('error', 'Your subscription is already in queue for cancellation.');
        }

        // cancel subscription
        if ($subscription->stripe_id !== null) {
            return self::successful($subscription, PaymentProvider::STRIPE);
        } else {
            return self::successful($subscription, PaymentProvider::PAYPAL);
        }
    }

    private static function successful(Subscription $subscription, PaymentProvider $provider): RedirectResponse
    {
        // dispatch the cancel subscription job
        CancelSubscriptionJob::dispatch($subscription, $provider);

        return redirect()
            ->route('home.subscription')
            ->with('success', 'Your subscription has been queued for cancellation.');
    }
}
