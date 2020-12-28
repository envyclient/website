<?php

namespace App\Http\Controllers\PayPal\Actions;

use App\Helpers\Paypal;
use App\Http\Controllers\Controller;
use App\Models\BillingAgreement;
use App\Models\Subscription;
use App\Notifications\SubscriptionCreated;
use App\Notifications\SubscriptionUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HandlePayPalWebhook extends Controller
{
    private $endpoint;

    public function __construct()
    {
        $this->middleware('valid-json-payload');
        $this->endpoint = config('paypal.endpoint');
    }

    public function __invoke(Request $request)
    {
        // verifying webhook
        $response = HTTP::withToken(Paypal::getAccessToken())
            ->withBody(json_encode([
                'auth_algo' => $request->header('PAYPAL-AUTH-ALGO'),
                'cert_url' => $request->header('PAYPAL-CERT-URL'),
                'transmission_id' => $request->header('PAYPAL-TRANSMISSION-ID'),
                'transmission_sig' => $request->header('PAYPAL-TRANSMISSION-SIG'),
                'transmission_time' => $request->header('PAYPAL-TRANSMISSION-TIME'),
                'webhook_id' => config('paypal.webhook_id'),
                'webhook_event' => $request->json()
            ]), 'application/json')
            ->post("$this->endpoint/v1/notifications/verify-webhook-signature");

        if ($response->status() !== 200) {
            return response()->json([
                'message' => 'Failed Webhook Signature'
            ], 400);
        }

        if ($request->json('event_type') === null) {
            return response()->json([
                'message' => 'Missing Billing Agreement'
            ], 400);
        }

        Log::debug($request->getContent());

        switch ($request->json('event_type')) {
            // received payment so we extend or create subscription
            case 'PAYMENT.SALE.COMPLETED':
            {
                // get the user that this billing id belongs to
                $billingAgreement = BillingAgreement::where(
                    'billing_agreement_id',
                    $request->json('resource.billing_agreement_id')
                )->firstOrFail();

                $user = $billingAgreement->user;

                if ($user->hasSubscription()) {
                    $user->subscription->fill([
                        'end_date' => $user->subscription->end_date->addDays(30)
                    ])->save();

                    // TODO: notify user about renewal
                } else {
                    Subscription::create([
                        'user_id' => $user->id,
                        'plan_id' => $billingAgreement->plan_id,
                        'billing_agreement_id' => $billingAgreement->id,
                        'end_date' => now()->addDays(30),
                    ]);

                    // email user about new subscription
                    $user->notify(new SubscriptionCreated());
                }

                break;
            }
            // user has cancelled their subscription
            case 'BILLING.SUBSCRIPTION.CANCELLED':
            {
                // get billing agreement
                $billingAgreement = BillingAgreement::where(
                    'billing_agreement_id',
                    $request->json('resource.id')
                )->firstOrFail();

                // cancel billing agreement by updating the state
                $billingAgreement->fill([
                    'state' => 'Cancelled'
                ])->save();

                // email user about subscription cancellation
                $billingAgreement->user->notify(new SubscriptionUpdated(
                    'Subscription Cancelled',
                    'Your subscription has been cancelled and you will not be charged at the next billing cycle.'
                ));

                break;
            }
            // payment has failed for a subscription
            case 'BILLING.SUBSCRIPTION.PAYMENT.FAILED':
            {
                // send request to paypal to cancel the billing agreement
                $response = Paypal::cancelBillingAgreement(
                    $request->json('resource.id'),
                    'Cancelling agreement due to payment fail.'
                );

                if ($response !== 204) {
                    Log::debug($response->json());
                    die(-1);
                }
                break;
            }
            default :
            {
                return response()->json([
                    'message' => 'Invalid Event Type'
                ], 400);
            }
        }

        return response()->json([
            'message' => '200 OK'
        ]);
    }
}
