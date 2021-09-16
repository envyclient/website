<?php

namespace App\Http\Controllers\Stripe\Actions;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\StripeSource;
use App\Models\StripeSourceEvent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class CreateStripeSource extends Controller
{
    public StripeClient $stripe;

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|exists:App\Models\Plan'
        ]);

        $user = $request->user();
        $plan = Plan::find($request->id);

        if ($user->hasSubscription()) {
            return back()->with('error', 'You already have a subscription.');
        }

        try {
            // check if the user still has an active stripe-source
            $source = StripeSource::where([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => StripeSource::PENDING,
            ])->firstOrFail();
        } catch (ModelNotFoundException) {
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
            } catch (ApiErrorException) {
                return back()->with('error', 'An error occurred.');
            }

            // store the stripe-source data in our database
            $source = StripeSource::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'source_id' => $response->id,
                'client_secret' => $response->client_secret,
                'status' => $response->status,
                'url' => $response->wechat['qr_code_url'],
            ]);

            // create the initial pending event in our database
            StripeSourceEvent::create([
                'stripe_source_id' => $source->id,
                'type' => StripeSource::PENDING,
                'message' => 'Payment initialized.',
            ]);
        }

        // redirect the user to the stripe-source page to show the QR code
        return redirect()->route('stripe-source.show', $source->source_id);
    }
}
