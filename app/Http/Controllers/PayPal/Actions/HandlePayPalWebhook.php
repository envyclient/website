<?php

namespace App\Http\Controllers\PayPal\Actions;

use App\Events\Subscription\SubscriptionCreatedEvent;
use App\Http\Controllers\Controller;
use App\Jobs\CancelSubscriptionJob;
use App\Jobs\SendDiscordWebhookJob;
use App\Models\Invoice;
use App\Models\Subscription;
use Illuminate\Http\Request;

class HandlePayPalWebhook extends Controller
{
    public function __invoke(Request $request)
    {
        $content = 'A webhook has been received.' . PHP_EOL . PHP_EOL;
        $content = $content . '**Provider**: PayPal' . PHP_EOL;
        $content = $content . '**Type**: ' . $request->json('event_type') . PHP_EOL;
        SendDiscordWebhookJob::dispatch($content);

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
                $subscription = Subscription::where('paypal_id', $request->json('resource.id'))
                    ->firstOrFail();

                // set the subscription as active
                $subscription->update([
                    'status' => Subscription::ACTIVE,
                    //'end_date' => Carbon::parse($request->json('resource.billing_info.next_billing_time')),
                ]);

                // broadcast new subscription event
                event(new SubscriptionCreatedEvent($subscription));

                break;
            }

            // payment received for the subscription
            case 'PAYMENT.SALE.COMPLETED':
            {

                // get the subscription
                $subscription = Subscription::where('paypal_id', $request->json('resource.billing_agreement_id'))
                    ->firstOrFail();

                // extend the subscription
                $subscription->update([
                    'end_date' => now()->addMonth()
                ]);

                // create invoice for the payment
                self::createInvoice(
                    $subscription->user->id,
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
                Subscription::where('paypal_id', $request->json('resource.id'))
                    ->firstOrFail()
                    ->update([
                        'state' => Subscription::CANCELED,
                    ]);

                break;
            }

            case 'PAYMENT.SALE.PENDING':  // paypal could not process the payment
            case 'BILLING.SUBSCRIPTION.SUSPENDED': // subscription run out of retries
            case 'BILLING.SUBSCRIPTION.PAYMENT.FAILED': // payment has failed for a subscription
            {

                // get the subscription
                $subscription = Subscription::where('paypal_id', $request->json('resource.id'))
                    ->firstOrFail();

                // mark the subscription as queued for cancellation
                $subscription->update([
                    'queued_for_cancellation' => true,
                ]);

                // dispatch the cancel subscription job
                CancelSubscriptionJob::dispatch($subscription, Invoice::PAYPAL);

                break;
            }
        }

        return response()->noContent();
    }
}
