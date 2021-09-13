<?php

namespace App\Http\Controllers\Stripe\Actions;

use App\Events\Subscription\SubscriptionCreatedEvent;
use App\Http\Controllers\Controller;
use App\Jobs\CancelSubscriptionJob;
use App\Jobs\SendDiscordWebhookJob;
use App\Models\Invoice;
use App\Models\StripeSession;
use App\Models\StripeSource;
use App\Models\StripeSourceEvent;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
                // getting the stripe session
                $stripeSession = StripeSession::where(
                    'stripe_session_id', $request->json('data.object.id')
                )->firstOrFail();

                // marking the stripe session as complete, so it is not auto pruned
                $stripeSession->update([
                    'completed_at' => now(),
                ]);

                // updating the users stripe customer id
                $stripeSession->user()->update([
                    'stripe_id' => $request->json('data.object.customer'),
                ]);
                break;
            }

            // Continue to provision the subscription as payments continue to be made.
            // Store the status in your database and check when a user accesses your service.
            // This approach helps you avoid hitting rate limits.
            case 'invoice.paid':
            {
                // getting the user the invoice is attached to
                $user = User::where('stripe_id', $request->json('data.object.customer'))
                    ->firstOrFail();

                if ($user->hasSubscription()) {

                    // extending the users' subscription by one month
                    $user->subscription->update([
                        'end_date' => now()->addMonth(),
                    ]);

                    // creating a new invoice for the payment
                    self::createInvoice(
                        $user->id,
                        $user->subscription->id,
                        Invoice::STRIPE,
                        $user->subscription->plan->price
                    );
                } else {

                    // getting to stripe session, so we can get the plan id the user subscribed to
                    $stripeSession = StripeSession::where('user_id', $user->id)
                        ->latest()
                        ->firstOrFail();

                    // creating a new subscription for the user
                    $subscription = Subscription::create([
                        'user_id' => $user->id,
                        'plan_id' => $stripeSession->plan_id,
                        'stripe_id' => $request->json('data.object.subscription'),
                        'stripe_status' => Subscription::ACTIVE,
                        'end_date' => now()->addMonth(),
                    ]);

                    self::createInvoice(
                        $user->id,
                        $subscription->id,
                        Invoice::STRIPE,
                        $subscription->plan->price
                    );

                    event(new SubscriptionCreatedEvent($subscription));
                }
                break;
            }

            // The payment failed or the customer does not have a valid payment method.
            // The subscription becomes past_due. Notify your customer and send them to the
            // customer portal to update their payment information.
            case 'invoice.payment_failed':
            {
                try {
                    // get the user related to webhook
                    $user = User::where('stripe_id', $request->json('data.object.customer'))
                        ->firstOrFail();

                    // if the user has a subscription then cancel it
                    if ($user->hasSubscription()) {
                        CancelSubscriptionJob::dispatch($user->subscription, Invoice::STRIPE);
                    }
                } catch (ModelNotFoundException) {
                    return response()->noContent();
                }
                break;
            }

            /**
             * Stripe Source (WeChat Pay)
             */

            // stripe-source object expired
            case 'source.canceled':
            {
                $source = StripeSource::where(
                    'source_id',
                    $request->json('data.object.id')
                )->firstOrFail();

                $source->update([
                    'status' => StripeSource::CANCELED,
                ]);

                self::createStripeSourceEvent(
                    $source->id,
                    StripeSource::CANCELED,
                    'The payment has expired. Please create a new one to continue your purchase.'
                );

                break;
            }

            // customer declined to authorize the payment
            case 'source.failed':
            {
                $source = StripeSource::where(
                    'source_id',
                    $request->json('data.object.id')
                )->firstOrFail();

                $source->update([
                    'status' => StripeSource::FAILED,
                ]);

                self::createStripeSourceEvent(
                    $source->id,
                    StripeSource::FAILED,
                    'The payment has been canceled.'
                );

                break;
            }

            // authorized and verified a payment
            case 'source.chargeable':
            {
                $source = StripeSource::where(
                    'source_id',
                    $request->json('data.object.id')
                )->firstOrFail();

                $source->update([
                    'status' => StripeSource::CHARGEABLE,
                ]);

                self::createStripeSourceEvent(
                    $source->id,
                    StripeSource::CHARGEABLE,
                    'The payment has been authorized.'
                );

                // charge the user
                \Stripe\Charge::create([
                    'amount' => $source->plan->cad_price,
                    'currency' => 'cad',
                    'source' => $source->source_id,
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

                $source = StripeSource::where(
                    'source_id',
                    $request->json('data.object.payment_method')
                )->firstOrFail();

                $source->update([
                    'status' => StripeSource::SUCCEEDED,
                ]);

                self::createStripeSourceEvent(
                    $source->id,
                    StripeSource::SUCCEEDED,
                    'You have successfully subscribed using WeChat Pay.'
                );

                // create subscription for the user
                $subscription = Subscription::create([
                    'user_id' => $source->user_id,
                    'plan_id' => $source->plan_id,
                    'end_date' => now()->addMonth(),
                ]);

                // creating a new invoice for the payment
                self::createInvoice(
                    $source->user_id,
                    $subscription->id,
                    Invoice::WECHAT,
                    $source->plan->price
                );

                event(new SubscriptionCreatedEvent($subscription));

                break;
            }

            // charge back
            case 'charge.dispute.created':
            {
                // TODO: handle dispute
                Log::debug($request->getContent());
                break;
            }
        }

        return response()->json(['status' => 'success']);
    }

    private static function createStripeSourceEvent(int $id, string $type, string $message)
    {
        StripeSourceEvent::create([
            'stripe_source_id' => $id,
            'type' => $type,
            'message' => $message,
        ]);
    }

}
