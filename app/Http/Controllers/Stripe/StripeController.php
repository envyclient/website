<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\StripeSession;
use App\Traits\Payment;
use Exception;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    use Payment;

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('not-subscribed')->except('success');
    }

    public function checkout(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|exists:App\Models\Plan',
        ]);

        $user = $request->user();
        $plan = Plan::find($request->id);

        try {
            $checkout_session = \Stripe\Checkout\Session::create([
                'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.cancel'),
                'customer_email' => $user->email,
                'payment_method_types' => ['card'],
                'mode' => 'subscription',
                'line_items' => [[
                    'price' => $plan->stripe_id,
                    'quantity' => 1,
                ]],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => [
                    'message' => $e->getError()->message,
                ],
            ], 400);
        }

        StripeSession::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'stripe_session_id' => $checkout_session['id'],
        ]);

        return response()->json(['sessionId' => $checkout_session['id']]);
    }

    public function success(Request $request)
    {
        // missing session_id in url
        if (!$request->has('session_id')) {
            return $this->failed();
        }

        return $this->successful();
    }

    public function cancel()
    {
        return $this->canceled();
    }
}
