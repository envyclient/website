<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\StripeSource;
use App\Models\StripeSourceEvent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Stripe\Exception\InvalidRequestException;
use Stripe\StripeClient;

class StripeSourcesController extends Controller
{
    public StripeClient $stripe;

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->stripe = new StripeClient(config('stripe.secret'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|exists:plans'
        ]);

        $user = $request->user();
        $plan = Plan::find($request->id);

        if ($user->hasSubscription()) {
            return back()->with('error', 'You already have a subscription.');
        }

        try {
            $source = StripeSource::where([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => 'pending'
            ])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            try {
                $response = $this->stripe->sources->create([
                    'type' => 'wechat',
                    'amount' => $plan->cad_price,
                    'currency' => 'cad',
                    'owner' => [
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                ]);
            } catch (InvalidRequestException $e) {
                return back()->with('error', 'An error occurred.');
            }

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
