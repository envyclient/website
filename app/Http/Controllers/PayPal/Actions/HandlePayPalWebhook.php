<?php

namespace App\Http\Controllers\PayPal\Actions;

use App\Enums\Invoice;
use App\Enums\PaymentProvider;
use App\Events\ReceivedWebhookEvent;
use App\Http\Controllers\Controller;
use App\Jobs\CancelSubscriptionJob;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HandlePayPalWebhook extends Controller
{
    public function __invoke(Request $request): Response
    {
        // broadcast the received webhook event
        event(new ReceivedWebhookEvent(PaymentProvider::PAYPAL, $request->json('event_type')));

        switch ($request->json('event_type')) {

            // subscription created for the user
            case 'BILLING.SUBSCRIPTION.CREATED':
            {
                break;
            }

            // subscription activated for the user
            case 'BILLING.SUBSCRIPTION.ACTIVATED':
            {

                // get the subscription
                $subscription = Subscription::query()
                    ->where('paypal_id', $request->json('resource.id'))
                    ->firstOrFail();

                // set the subscription as active
                $subscription->update([
                    'status' => \App\Enums\Subscription::ACTIVE->value,
                    //'end_date' => Carbon::parse($request->json('resource.billing_info.next_billing_time')),
                ]);

                break;
            }

            // payment received for the subscription
            case 'PAYMENT.SALE.COMPLETED':
            {

                // get the subscription model
                $subscription = Subscription::query()
                    ->where('paypal_id', $request->json('resource.billing_agreement_id'))
                    ->firstOrFail();

                // extend the subscription
                $subscription->update([
                    'end_date' => now()->addMonth()
                ]);

                // create invoice for the payment
                self::createInvoice(
                    $subscription->id,
                    Invoice::PAYPAL,
                    $subscription->plan->price
                );

                break;
            }

            // user has cancelled their subscription
            case 'BILLING.SUBSCRIPTION.CANCELLED':
            {

                // set the subscription as cancelled
                Subscription::query()
                    ->where('paypal_id', $request->json('resource.id'))
                    ->update([
                        'status' => \App\Enums\Subscription::CANCELED->value,
                    ]);

                break;
            }

            case 'PAYMENT.SALE.PENDING': // paypal could not process the payment
            case 'BILLING.SUBSCRIPTION.SUSPENDED': // subscription run out of retries
            case 'BILLING.SUBSCRIPTION.PAYMENT.FAILED': // payment has failed for a subscription
            {

                try {

                    // get the subscription
                    $subscription = Subscription::query()
                        ->where('paypal_id', $request->json('resource.id'))
                        ->firstOrFail();

                    // dispatch the cancel subscription job
                    CancelSubscriptionJob::dispatch($subscription, PaymentProvider::PAYPAL);

                } catch (ModelNotFoundException) {
                }

                break;
            }
        }

        return response()->noContent();
    }
}
