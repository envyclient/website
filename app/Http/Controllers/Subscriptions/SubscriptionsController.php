<?php

namespace App\Http\Controllers\Subscriptions;

use App\Helpers\Paypal;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'subscribed']);
    }

    public function delete(Request $request)
    {
        $user = $request->user();

        // user did not subscribe using paypal
        if (!$user->hasBillingAgreement()) {
            return back()->with('error', 'Since you do not have a PayPal subscription there is no need to cancel.');
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
