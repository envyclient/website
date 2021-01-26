<?php

namespace App\Http\Controllers\Stripe\Actions;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\VerifyStripeWebhookSignature;
use App\Models\Invoice;
use App\Models\StripeSession;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\Subscription\SubscriptionCreated;
use App\Notifications\Subscription\SubscriptionUpdated;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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
        }

        return response()->noContent();
    }

}