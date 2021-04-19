<?php

namespace App\Http\Controllers\PayPal\Actions;

use App\Helpers\Paypal;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\VerifyPaypalWebhookSignature;
use App\Models\BillingAgreement;
use App\Models\Invoice;
use App\Models\Subscription;
use App\Notifications\Subscription\SubscriptionCreated;
use App\Notifications\Subscription\SubscriptionUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HandlePayPalWebhook extends Controller
{
    private $endpoint;

    public function __construct()
    {
        $this->middleware(VerifyPaypalWebhookSignature::class);
        $this->endpoint = config('paypal.endpoint');
    }

    public function __invoke(Request $request)
    {
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
                    $user->subscription->update([
                        'end_date' => now()->addMonth()
                    ]);

                    Invoice::create([
                        'user_id' => $user->id,
                        'subscription_id' => $user->subscription->id,
                        'method' => Invoice::PAYPAL,
                        'price' => $user->subscription->plan->price,
                    ]);

                    $user->notify(new SubscriptionUpdated(
                        'Subscription Renewed',
                        'Your subscription for Envy Client has been renewed.'
                    ));
                } else {
                    $subscription = Subscription::create([
                        'user_id' => $user->id,
                        'plan_id' => $billingAgreement->plan_id,
                        'billing_agreement_id' => $billingAgreement->id,
                        'end_date' => now()->addMonth(),
                    ]);

                    Invoice::create([
                        'user_id' => $user->id,
                        'subscription_id' => $subscription->id,
                        'method' => Invoice::PAYPAL,
                        'price' => $billingAgreement->plan->price,
                    ]);

                    // email user about new subscription
                    $user->notify(new SubscriptionCreated());

                    event(new \App\Events\SubscriptionCreated($subscription));
                }

                break;
            }

            // paypal could not process the payment
            case 'PAYMENT.SALE.PENDING':
            {
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
                $billingAgreement->update([
                    'state' => 'Cancelled',
                ]);

                // email user about subscription cancellation
                $billingAgreement->user->notify(new SubscriptionUpdated(
                    'Subscription Cancelled',
                    'Your subscription has been cancelled and you will not be charged at the next billing cycle.'
                ));

                break;
            }

            case 'BILLING.SUBSCRIPTION.SUSPENDED': // subscription run out of retries
            case 'BILLING.SUBSCRIPTION.PAYMENT.FAILED': // payment has failed for a subscription
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
        }

        return response()->noContent();
    }
}
