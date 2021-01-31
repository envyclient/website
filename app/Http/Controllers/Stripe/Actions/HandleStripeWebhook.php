<?php

namespace App\Http\Controllers\Stripe\Actions;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\VerifyStripeWebhookSignature;
use App\Models\Invoice;
use App\Models\StripeSession;
use App\Models\StripeSource;
use App\Models\StripeSourceEvent;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\Subscription\SubscriptionCreated;
use App\Notifications\Subscription\SubscriptionUpdated;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HandleStripeWebhook extends Controller
{
    public function __construct()
    {
        $this->middleware(VerifyStripeWebhookSignature::class);
    }

    public function __invoke(Request $request)
    {
        // Handle the event
        switch ($request->json('type')) {

            /**
             * Stripe Checkout (CC & GPay)
             */

            // Send after checkout completion
            case 'checkout.session.completed':
            {
                // getting the stripe session
                $stripeSession = StripeSession::where(
                    'stripe_session_id', $request->json('data.object.id')
                )->firstOrFail();

                // updating the users stripe customer id
                $stripeSession->user()->update([
                    'stripe_id' => $request->json('data.object.customer'),
                ]);
                break;
            }

            // Sent each billing interval when a payment succeeds.
            case 'invoice.paid':
            {
                $user = User::where(
                    'stripe_id', $request->json('data.object.customer')
                )->firstOrFail();

                $stripeSession = StripeSession::where('user_id', $user->id)
                    ->latest()
                    ->firstOrFail();

                if ($user->hasSubscription()) {
                    $user->subscription->update([
                        'end_date' => now()->addMonth()
                    ]);

                    Invoice::create([
                        'user_id' => $user->id,
                        'subscription_id' => $user->subscription->id,
                        'method' => 'stripe',
                        'price' => $user->subscription->plan->price,
                    ]);

                    $user->notify(new SubscriptionUpdated(
                        'Subscription Renewed',
                        'Your subscription for Envy Client has been renewed.'
                    ));
                } else {
                    $subscription = Subscription::create([
                        'user_id' => $user->id,
                        'plan_id' => $stripeSession->plan_id,
                        'stripe_id' => $request->json('data.object.subscription'),
                        'stripe_status' => 'Active',
                        'end_date' => now()->addMonth(),
                    ]);

                    Invoice::create([
                        'user_id' => $user->id,
                        'subscription_id' => $subscription->id,
                        'method' => 'stripe',
                        'price' => $subscription->plan->price,
                    ]);

                    // email user about new subscription
                    $user->notify(new SubscriptionCreated());
                }
                break;
            }

            // Sent each billing interval if there is an issue with your customer’s payment method.
            case 'invoice.payment_failed':
            {
                try {
                    $user = User::where(
                        'stripe_id', $request->json('data.object.customer')
                    )->firstOrFail();

                    if ($user->hasSubscription()) {
                        $user->subscription->update([
                            'stripe_status' => 'Cancelled',
                        ]);
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
                    'status' => 'cancelled',
                ]);

                self::createEvent(
                    $source->id,
                    'canceled',
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
                    'status' => 'failed',
                ]);

                self::createEvent(
                    $source->id,
                    'failed',
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
                    'status' => 'chargeable',
                ]);

                self::createEvent(
                    $source->id,
                    'chargeable',
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
                    'status' => 'succeeded',
                ]);

                self::createEvent(
                    $source->id,
                    'succeeded',
                    'You have successfully subscribed using WeChat Pay.'
                );

                // create subscription for the user
                $subscription = Subscription::create([
                    'user_id' => $source->user_id,
                    'plan_id' => $source->plan_id,
                    'end_date' => now()->addMonth(),
                ]);

                Invoice::create([
                    'user_id' => $source->user_id,
                    'subscription_id' => $subscription->id,
                    'method' => 'wechat',
                    'price' => $source->plan->price,
                ]);
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

        return response()->noContent();
    }

    private static function createEvent(int $id, string $type, string $message)
    {
        StripeSourceEvent::create([
            'stripe_source_id' => $id,
            'type' => $type,
            'message' => $message,
        ]);
    }

}
