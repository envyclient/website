<?php

namespace App\Http\Controllers\StripeSource\Actions;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\StripeSource;
use App\Models\StripeSourceEvent;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use UnexpectedValueException;

class HandleStripeSourcesWebhook extends Controller
{
    public function __construct()
    {
        $this->middleware('valid-json-payload');
    }

    public function __invoke(Request $request)
    {
        $event = null;
        try {
            $event = \Stripe\Webhook::constructEvent(
                $request->getContent(),
                $request->header('stripe-signature'),
                config('stripe.webhook.secret')
            );
        } catch (UnexpectedValueException) {
            return response()->json([
                'message' => 'Invalid Payload',
            ], 400);
        } catch (SignatureVerificationException) {
            return response()->json([
                'message' => 'Invalid Signature',
            ], 400);
        }

        // Handle the event
        switch ($event->type) {
            // object expired
            case 'source.canceled':
            {
                $source = StripeSource::where(
                    'source_id',
                    $event->data->object->id
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
                    $event->data->object->id
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
                    $event->data->object->id
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
                $source = StripeSource::where(
                    'source_id',
                    $event->data->object->payment_method
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
                    'billing_agreement_id' => null,
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
                Log::debug($event->toJSON());
                break;
            }
        }

        return response()->json([
            'message' => '200 OK',
        ]);
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
