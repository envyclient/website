<?php

namespace App\Http\Controllers;

use App\Notifications\SubscriptionUpdated;
use App\Subscription;
use Carbon\Carbon;
use Exception;
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

        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => 1,
            'end_date' => Carbon::now()->addDecade(),
        ]);

        $user->notify(new SubscriptionUpdated(
            'New Subscription',
            'Thank you for subscribing to the free plan.'
        ));

        return back()->with('success', 'Subscribed to the free plan.');
    }

    public function cancel(Request $request)
    {
        $user = $request->user();
        if (!$user->hasSubscription()) {
            return back()->with('error', 'You must subscribe to a plan first.');
        }

        if ($user->subscribedToFreePlan()) {
            Subscription::where('user_id', $user->id)->delete();

            $user->notify(new SubscriptionUpdated(
                'Subscription Cancelled',
                'You have cancelled your free subscription. Please renew it if you wish to continue using the client.'
            ));

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
