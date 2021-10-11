<?php

namespace App\Http\Controllers\Stripe\Actions;

use App\Enums\StripeSource;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class CreateStripeSource extends Controller
{
    public StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret')); // TODO: use laravel singleton
    }

    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'id' => ['required', 'integer', 'exists:plans'],
        ]);

        $user = $request->user();
        $plan = Plan::find($request->id);

        // create new stripe source
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

        // store the stripe source
        Cache::put($response->id, [
            'user_id' => $user->id,
            'plan' => $plan->only('id', 'name', 'price', 'cad_price'),
            'status' => StripeSource::PENDING,
            'url' => $response->wechat['qr_code_url'],
            'events' => [
                [
                    'type' => StripeSource::PENDING,
                    'message' => 'Payment initialized.',
                    'created_at' => now(),
                ],
            ],
        ], now()->addHours(2));

        // redirect the user to the stripe-source page to show the QR code
        return redirect()->route('stripe-source.show', $response->id);
    }
}
