<?php

namespace App\Http\Controllers\Stripe\Actions;

use App\Enums\Invoice;
use App\Enums\StripeSource;
use App\Events\Subscription\SubscriptionCreatedEvent;
use App\Http\Controllers\Controller;
use App\Jobs\CancelSubscriptionJob;
use App\Jobs\SendDiscordWebhookJob;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HandleStripeWebhook extends Controller
{
    public function __invoke(Request $request)
    {
        $content = 'A webhook has been received.' . PHP_EOL . PHP_EOL;
        $content = $content . '**Provider**: Stripe' . PHP_EOL;
        $content = $content . '**Type**: ' . $request->json('type') . PHP_EOL;
        SendDiscordWebhookJob::dispatch($content);

        // Handle the event
        switch ($request->json('type')) {

            /**
             * Stripe Checkout (CC)
             */

            // Payment is successful and the subscription is created.
            // You should provision the subscription and save the customer ID to your database.
            case 'checkout.session.completed':
            {
                // getting the stripe checkout session from the cache
                ['user_id' => $userId, 'plan_id' => $planID] = Cache::get($request->json('data.object.id'));

                // create a pending subscription
                $subscription = Subscription::create([
                    'user_id' => $userId,
                    'plan_id' => $planID,
                    'stripe_id' => $request->json('data.object.subscription'),
                    'status' => \App\Enums\Subscription::PENDING,
                ]);

                // broadcast new subscription event
                event(new SubscriptionCreatedEvent($subscription));

                break;
            }

            // Continue to provision the subscription as payments continue to be made.
            // Store the status in your database and check when a user accesses your service.
            // This approach helps you avoid hitting rate limits.
            case 'invoice.paid':
            {

                // get the subscription model
                $subscription = Subscription::query()
                    ->where('stripe_id', $request->json('data.object.subscription'))
                    ->firstOrFail();

                // activate the subscription and set end_date
                $subscription->update([
                    'status' => \App\Enums\Subscription::ACTIVE,
                    'end_date' => now()->addMonth(),
                ]);

                self::createInvoice(
                    $subscription->id,
                    Invoice::STRIPE,
                    $subscription->plan->price
                );

                break;
            }

            // The payment failed or the customer does not have a valid payment method.
            // The subscription becomes past_due. Notify your customer and send them to the
            // customer portal to update their payment information.
            case 'invoice.payment_failed':
            {

                try {

                    // get the related subscription
                    $subscription = Subscription::query()
                        ->where('stripe_id', $request->json('data.object.subscription'))
                        ->firstOrFail();

                    // queue the subscription for cancellation
                    CancelSubscriptionJob::dispatch($subscription, Invoice::STRIPE);

                } catch (ModelNotFoundException) {
                }

                break;
            }

            /**
             * Stripe Source (WeChat Pay)
             */

            // stripe-source object expired
            case 'source.canceled':
            {

                self::handleStripeSource(
                    $request->json('data.object.id'),
                    StripeSource::CANCELED,
                    'The payment has expired. Please create a new one to continue your purchase.'
                );


                break;
            }

            // customer declined to authorize the payment
            case 'source.failed':
            {

                self::handleStripeSource(
                    $request->json('data.object.id'),
                    StripeSource::FAILED,
                    'The payment has been canceled.'
                );

                break;
            }

            // authorized and verified a payment
            case 'source.chargeable':
            {
                // getting the stripe source id
                $id = $request->json('data.object.id');

                $source = self::handleStripeSource(
                    $id,
                    StripeSource::CHARGEABLE,
                    'The payment has been authorized.'
                );

                // charging the user
                \Stripe\Charge::create([
                    'amount' => $source['plan']['cad_price'],
                    'currency' => 'cad',
                    'source' => $id,
                ]);

                break;
            }

            // charge succeeded and the payment is complete
            case 'charge.succeeded':
            {
                // user subscribed using checkout not stripe-source
                if ($request->json('data.object.customer') !== null) {
                    return response()->noContent();
                }

                $source = self::handleStripeSource(
                    $request->json('data.object.payment_method'),
                    StripeSource::SUCCEEDED,
                    'You have successfully subscribed using WeChat Pay.'
                );

                // create subscription for the user
                $subscription = Subscription::create([
                    'user_id' => $source['user_id'],
                    'plan_id' => $source['plan']['id'],
                    'status' => \App\Enums\Subscription::CANCELED,
                    'end_date' => now()->addMonth(),
                ]);

                // creating a new invoice for the payment
                self::createInvoice(
                    $subscription->id,
                    Invoice::WECHAT,
                    $source['plan']['price']
                );

                event(new SubscriptionCreatedEvent($subscription));

                break;
            }

            // charge back
            case 'charge.dispute.created':
            {
                // TODO: handle dispute
                info($request->getContent());
                break;
            }
        }

        return response()->json(['status' => 'success']);
    }

    private static function handleStripeSource(string $stripeSource, string $status, string $message): array
    {
        // get the source
        $source = Cache::get($stripeSource);

        // checking the stripe source is still cached
        if ($source === null) {
            abort(404);
        }

        // update the source
        $source['status'] = $status;
        array_push($source['events'], [
            'type' => $status,
            'message' => $message,
            'created_at' => now(),
        ]);

        // store the updated source
        Cache::put($stripeSource, $source, now()->addHours(2));

        return $source;
    }

}
