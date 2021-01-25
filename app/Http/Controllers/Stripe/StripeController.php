<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\StripeSession;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'not-subscribed'])->except('billingPortal');
        $this->middleware('subscribed')->only('billingPortal');
    }

    public function checkout(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|exists:plans'
        ]);

        $plan = Plan::where('id', $request->id)->first();

        try {
            $checkout_session = Session::create([
                'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.cancel'),
                'customer_email' => $request->user()->email,
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
            'user_id' => auth()->id(),
            'plan_id' => $plan->id,
            'stripe_session_id' => $checkout_session['id'],
        ]);

        return response()->json(['sessionId' => $checkout_session['id']]);
    }

    public function success(Request $request)
    {
        if (!$request->has('session_id')) {
            abort(400);
        }
        return redirect(RouteServiceProvider::SUBSCRIPTIONS)->with('success', 'Subscription successful. Please allow up to 5 minutes to process.');
    }

    public function cancel()
    {
        return redirect(RouteServiceProvider::SUBSCRIPTIONS)->with('error', 'Subscription cancelled.');
    }

    // take the user to their stripe billing portal
    public function billingPortal(Request $request)
    {
        $user = $request->user();
        if ($user->stripe_id === null) {
            return back();
        }

        $session = \Stripe\BillingPortal\Session::create([
            'customer' => $user->stripe_id,
            'return_url' => url('/'),
        ]);

        return redirect()->away($session->url);
    }
}
