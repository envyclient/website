<?php

namespace App\Http\Controllers;

use App\Notifications\Generic;
use Exception;
use Illuminate\Http\Request;
use PayPal\Api\Agreement;
use PayPal\Api\AgreementStateDescriptor;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class CancelSubscription extends Controller
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

    public function __invoke(Request $request)
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

        // email user about subscription cancellation
        $user->notify(new Generic($user, 'You have successfully cancelled your subscription, you will not be charged at the next billing cycle.', 'Subscription'));

        return back()->with('success', 'Your subscription has been cancelled and will not renew.');
    }
}
