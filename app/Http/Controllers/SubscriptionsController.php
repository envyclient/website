<?php

namespace App\Http\Controllers;

use App\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PayPal\Api\Agreement;
use PayPal\Api\AgreementStateDescriptor;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class SubscriptionsController extends Controller
{
    private $paypal;

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);

        $this->paypal = new ApiContext(new OAuthTokenCredential(
            config('paypal.client_id'),
            config('paypal.secret')
        ));
        $this->paypal->setConfig(config('paypal.settings'));
    }

    public function free(Request $request)
    {
        $user = $request->user();

        if (!$user->access_free_plan) {
            return back();
        }

        if ($user->hasSubscription()) {
            return back()->with('error', 'You already have a subscription.');
        }

        // creating a new subscription for the user
        $subscription = new Subscription();
        $subscription->user_id = $user->id;
        $subscription->plan_id = null;
        $subscription->billing_agreement_id = null;
        $subscription->end_date = Carbon::now()->addCenturies(1);
        $subscription->save();

        return back()->with('success', 'Subscribed to the free plan.');
    }

    public function cancel(Request $request)
    {
        $user = $request->user();

        if (!$user->hasSubscription()) {
            return back()->with('error', 'You must subscribe to a plan first.');
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
            die($e);
        }

        return back()->with('success', 'Your subscription has been queued to cancel and will not renew at the end of billing period.');
    }
}
