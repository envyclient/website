<?php

namespace App\Http\Controllers\Stripe\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

class HandleStripeWebhook extends Controller
{
    public function __construct()
    {
        $this->middleware('valid-json-payload');
    }

    public function __invoke(Request $request)
    {
        $event = null;
        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                $request->header('stripe-signature'),
                'whsec_2bz3SLhmNlIRxcIzkOKZ6YHxQcpR7wE2'
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
            case 'checkout.session.completed':
            {
                break;
            }
            // Sent each billing interval when a payment succeeds.
            case 'invoice.paid':
            {
                break;
            }
            // Sent each billing interval if there is an issue with your customer’s payment method.
            case 'invoice.payment_failed':
            {
                break;
            }
        }

        return response()->json([
            'message' => '200 OK',
        ]);
    }

}
