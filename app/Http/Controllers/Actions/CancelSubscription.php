<?php

namespace App\Http\Controllers\Actions;

use App\Helpers\Paypal;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class CancelSubscription extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'subscribed']);
    }

    public function __invoke(Request $request)
    {
        $user = $request->user();

        // cancel stripe subscription
        if ($user->subscription->stripe_id !== null) {
            $stripe = new StripeClient(config('stripe.secret'));
            $stripe->subscriptions->cancel(
                $user->subscription->stripe_id,
                []
            );
            $user->subscription->update([
                'stripe_status' => Subscription::CANCELED,
            ]);
            return redirect()
                ->route('home.subscription')
                ->with('success', 'You subscription has been cancelled.');
        }

        // user did not subscribe using paypal or cc
        if (!$user->hasBillingAgreement()) {
            return redirect()
                ->route('home.subscription')
                ->with('error', 'You do not need to cancel your subscription.');
        }

        if ($user->isBillingAgreementCancelled()) {
            return redirect()
                ->route('home.subscription')
                ->with('error', 'You have already cancelled your subscription.');
        }

        $response = Paypal::cancelBillingAgreement(
            $user->billingAgreement->billing_agreement_id,
            'User cancelled subscription.'
        );

        if ($response !== 204) {
            return redirect()
                ->route('home.subscription')
                ->with('error', 'You have already cancelled your subscription.');
        }

        return redirect()
            ->route('home.subscription')
            ->with('success', 'Your subscription has been queued for cancellation.');
    }
}
