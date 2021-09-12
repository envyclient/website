<?php

namespace App\Http\Controllers\PayPal\Actions;

use App\Events\Subscription\SubscriptionCancelledEvent;
use App\Events\Subscription\SubscriptionCreatedEvent;
use App\Helpers\Paypal;
use App\Http\Controllers\Controller;
use App\Jobs\SendDiscordWebhookJob;
use App\Models\BillingAgreement;
use App\Models\Invoice;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HandlePayPalWebhook extends Controller
{
    public function __invoke(Request $request)
    {
        $content = 'A webhook has been received.' . PHP_EOL . PHP_EOL;
        $content = $content . '**Provider**: PayPal' . PHP_EOL;
        $content = $content . '**Type**: ' . $request->json('event_type') . PHP_EOL;
        SendDiscordWebhookJob::dispatch($content);

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

                    self::createInvoice(
                        $user->id,
                        $user->subscription->id,
                        Invoice::PAYPAL,
                        $user->subscription->plan->price
                    );
                } else {
                    $subscription = Subscription::create([
                        'user_id' => $user->id,
                        'plan_id' => $billingAgreement->plan_id,
                        'billing_agreement_id' => $billingAgreement->id,
                        'end_date' => now()->addMonth(),
                    ]);

                    self::createInvoice(
                        $user->id,
                        $subscription->id,
                        Invoice::PAYPAL,
                        $billingAgreement->plan->price
                    );

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
                $billingAgreement = BillingAgreement::where('billing_agreement_id', $request->json('resource.id'))
                    ->firstOrFail()
                    ->update([
                        'state' => Subscription::CANCELED,
                    ]);

                // broadcast the subscription cancelled event
                event(new SubscriptionCancelledEvent($billingAgreement->subscription));

                break;
            }

            case 'BILLING.SUBSCRIPTION.SUSPENDED': // subscription run out of retries
            case 'BILLING.SUBSCRIPTION.PAYMENT.FAILED': // payment has failed for a subscription
            {
                // tell PayPal to cancel the users billing agreement
                $response = Paypal::cancelBillingAgreement(
                    $request->json('resource.id'),
                    'Cancelling agreement due to payment fail.'
                );

                if ($response->failed()) {
                    Log::debug($response);
                    die(-1);
                }
                break;
            }
        }

        return response()->noContent();
    }
}
