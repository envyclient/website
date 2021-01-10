<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\StripeSource;
use App\Models\StripeSourceEvent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class StripeSourcesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'plan-id' => 'required|integer|exists:plans,id'
        ]);

        $user = $request->user();
        $plan = Plan::find($request->input('plan-id'));

        if ($user->hasSubscription()) {
            return back()->with('error', 'You already have a subscription.');
        }

        try {
            $source = StripeSource::where([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => 'pending'
            ])->firstOrFail();
        } catch (ModelNotFoundException) {

            $stripe = new StripeClient(config('stripe.secret'));
            $response = $stripe->sources->create([
                "type" => "wechat",
                "amount" => $plan->one_time_price,
                "currency" => "usd",
                "owner" => [
                    "name" => $user->name,
                    "email" => $user->email,
                ],
            ]);

            $source = StripeSource::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'source_id' => $response->id,
                'client_secret' => $response->client_secret,
                'status' => $response->status,
                'url' => $response->wechat['qr_code_url'],
            ]);

            // creating the initial event
            StripeSourceEvent::create([
                'stripe_source_id' => $source->id,
                'type' => 'pending',
                'message' => 'Payment initialized.',
            ]);
        }

        return redirect(
            route('stripe.show', $source->source_id)
        );
    }
}
