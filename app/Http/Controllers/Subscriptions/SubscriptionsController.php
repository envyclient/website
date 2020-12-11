<?php

namespace App\Http\Controllers\Subscriptions;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Exception;
use Illuminate\Http\Request;
use PayPal\Api\Agreement;
use PayPal\Api\AgreementStateDescriptor;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class SubscriptionsController extends Controller
{
    protected $paypal;

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);

        $this->paypal = new ApiContext(new OAuthTokenCredential(
            config('paypal.client_id'),
            config('paypal.secret')
        ));
        $this->paypal->setConfig(config('paypal.settings'));
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

        if ($user->billingAgreement->state !== 'Active') {
            return back()->with('error', 'You have already cancelled your subscription.');
        }

        // cancel billing agreement
        $agreementStateDescriptor = new AgreementStateDescriptor();
        $agreementStateDescriptor->setNote("User cancelled subscription.");

        try {
            $agreement = Agreement::get($user->billingAgreement->billing_agreement_id, $this->paypal);
            $agreement->cancel($agreementStateDescriptor, $this->paypal);
        } catch (Exception $e) {
            return back()->with('error', 'An error occurred while cancelling your subscription. This is most likely due to your subscription already in queue for cancellation.');
        }

        return back()->with('success', 'Your subscription has been queued to cancel and will not renew at the end of billing period.');
    }
}
