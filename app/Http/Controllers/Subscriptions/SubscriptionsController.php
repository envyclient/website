<?php

namespace App\Http\Controllers\Subscriptions;

use App\Helpers\Paypal;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Notifications\Subscription\SubscriptionUpdated;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function delete(Request $request)
    {
        $user = $request->user();
        if (!$user->hasSubscription()) {
            return back()->with('error', 'You must subscribe to a plan first.');
        }

        if ($user->subscribedToFreePlan()) {
            Subscription::where('user_id', $user->id)->delete();
            return back()->with('success', 'You have cancelled your free subscription.');
        }

        if ($user->isBillingAgreementCancelled()) {
            return back()->with('error', 'You have already cancelled your subscription.');
        }

        $response = Paypal::cancelBillingAgreement(
            $user->billingAgreement->billing_agreement_id,
            'User cancelled subscription.'
        );

        if ($response !== 204) {
            return back()->with(
                'error',
                'An error occurred while cancelling your subscription. This is most likely due to your subscription already in queue for cancellation.'
            );
        }

        return back()->with('success', 'Your subscription has been queued to cancel and will not renew at the end of billing period.');
    }
}
