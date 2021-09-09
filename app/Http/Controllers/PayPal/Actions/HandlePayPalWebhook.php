<?php

namespace App\Http\Controllers\PayPal\Actions;

use App\Events\SubscriptionCreatedEvent;
use App\Helpers\Paypal;
use App\Http\Controllers\Controller;
use App\Models\BillingAgreement;
use App\Models\Invoice;
use App\Models\Subscription;
use App\Notifications\Subscription\SubscriptionCreatedNotification;
use App\Notifications\Subscription\SubscriptionUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HandlePayPalWebhook extends Controller
{
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

                    self::createInvoice($user->id, $user->subscription->id, Invoice::PAYPAL, $user->subscription->plan->price);

                    $user->notify(new SubscriptionUpdatedNotification(
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

                    self::createInvoice($user->id, $subscription->id, Invoice::PAYPAL, $billingAgreement->plan->price);

                    // email user about new subscription
                    $user->notify(new SubscriptionCreatedNotification());

                    event(new SubscriptionCreatedEvent($subscription));
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
                $billingAgreement->user->notify(new SubscriptionUpdatedNotification(
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
                    Log::debug($response);
                    die(-1);
                }
                break;
            }
        }

        return response()->noContent();
    }
}
