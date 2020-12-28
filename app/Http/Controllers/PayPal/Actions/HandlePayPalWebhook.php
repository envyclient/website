<?php

namespace App\Http\Controllers\PayPal\Actions;

use App\Helpers\Paypal;
use App\Http\Controllers\Controller;
use App\Models\BillingAgreement;
use App\Models\Subscription;
use App\Notifications\SubscriptionCreated;
use App\Notifications\SubscriptionUpdated;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HandlePayPalWebhook extends Controller
{
    private $endpoint;

    public function __construct()
    {
        $this->endpoint = config('paypal.endpoint');
    }

    public function __invoke(Request $request)
    {
        // TODO: check webhook id

        if (empty($request->getContent())) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        $data = json_decode($request->getContent());

        // TODO: handle webhook
        if (!isset($data->event_type)) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        Log::debug($request->getContent());

        switch ($data->event_type) {
            case 'PAYMENT.SALE.COMPLETED': // received payment so we extend or create subscription
            {
                // get the user that this billing id belongs to
                $billingAgreement = BillingAgreement::where('billing_agreement_id', $data->resource->billing_agreement_id)
                    ->firstOrFail();

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
                        'end_date' => Carbon::now()->addDays(30),
                    ]);

                    // email user about new subscription
                    $user->notify(new SubscriptionCreated());
                }

                break;
            }
            case 'BILLING.SUBSCRIPTION.CANCELLED': // user has cancelled their subscription
            {
                // get billing agreement
                $billingAgreement = BillingAgreement::where('billing_agreement_id', $data->resource->id)
                    ->firstOrFail();

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
            case 'BILLING.SUBSCRIPTION.PAYMENT.FAILED': // payment has failed for a subscription
            {
                $response = Paypal::cancelBillingAgreement(
                    $data->resource->id,
                    'Cancelling agreement due to payment fail.'
                );

                if ($response !== 204) {
                    Log::debug($response->json());
                    die(-1);
                }
                break;
            }
        }

        return response()->json([
            'message' => '200 OK'
        ]);
    }
}
