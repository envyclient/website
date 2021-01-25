<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function checkout(Request $request)
    {
        try {
            $checkout_session = \Stripe\Checkout\Session::create([
                'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.cancel'),
                'payment_method_types' => ['card'],
                'mode' => 'subscription',
                'line_items' => [[
                    'price' => $request->price_id, // TODO: get id from plan model
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
        return response()->json(['sessionId' => $checkout_session['id']]);
    }

    public function success(Request $request)
    {
        if (!$request->has('session_id')) {
            abort(400);
        }

        // TODO: create session

        dd($request->get('session_id'));
    }

    public function cancel()
    {
        return redirect(RouteServiceProvider::SUBSCRIPTIONS)->with('error', 'Subscription cancelled.');
    }
}
