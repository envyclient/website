<?php

namespace App\Http\Controllers\API;

use App\BillingAgreement;
use App\Http\Controllers\Controller;
use App\Notifications\NewSubscription;
use App\Subscription;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use PayPal\Api\Agreement;
use PayPal\Api\AgreementStateDescriptor;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class HandlePayPalWebhook extends Controller
{
    private $paypal;

    public function __construct()
    {
        $this->middleware('api');

        $this->paypal = new ApiContext(new OAuthTokenCredential(
            config('paypal.client_id'),
            config('paypal.secret')
        ));
        $this->paypal->setConfig(config('paypal.settings'));
    }

    public function __invoke(Request $request)
    {
        // TODO: handle webhook
        if (!$request->has('event_type')) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        switch (strtolower($request->event_type)) {
            case 'PAYMENT.SALE.COMPLETED': // received payment so we extend or create subscription
            {
                // get the user that this billing id belongs to
                $billingAgreement = BillingAgreement::where('billing_agreement_id', $request->resource->billing_agreement_id)->firstOrFail();
                $user = $billingAgreement->user;

                // TODO: handle plan switching

                if ($user->hasSubscription()) {
                    // extended the subscription
                    $user->subscription->end_date = $user->subscription->end_date->addDays(30);
                    $user->subscription->save();

                    // TODO: notify user about renewal
                } else {
                    // creating a new subscription for the user
                    $subscription = new Subscription();
                    $subscription->user_id = $user->id;
                    $subscription->plan_id = $billingAgreement->plan_id;
                    $subscription->billing_agreement_id = $billingAgreement->plan_id;
                    $subscription->end_date = Carbon::now()->addDays(30);
                    $subscription->save();

                    $user->notify(new NewSubscription($user));
                }

                break;
            }
            case 'BILLING.SUBSCRIPTION.CANCELLED': // user has cancelled their subscription
            {
                // get billing agreement
                $billingAgreement = BillingAgreement::where('billing_agreement_id', $request->resource->id)->firstOrFail();

                // cancel billing agreement
                $billingAgreement->state = $request->resource->state;
                $billingAgreement->save();

                break;
            }
            case 'BILLING.SUBSCRIPTION.PAYMENT.FAILED': // payment has failed for a subscription
            {
                $agreementStateDescriptor = new AgreementStateDescriptor();
                $agreementStateDescriptor->setNote("Cancelling agreement due to payment fail.");

                try {
                    $agreement = Agreement::get($request->resource->id, $this->paypal);
                    $agreement->cancel($agreementStateDescriptor, $this->paypal);
                } catch (Exception $e) {
                    die($e);
                }
                break;
            }
        }

        return response()->json([
            'message' => '200 OK'
        ]);
    }
}
