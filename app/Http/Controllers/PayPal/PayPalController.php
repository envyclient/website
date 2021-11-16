<?php

namespace App\Http\Controllers\PayPal;

use App\Events\Subscription\SubscriptionCreatedEvent;
use App\Helpers\Paypal;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Traits\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PayPalController extends Controller
{
    use Payment;

    private string $endpoint;

    public function __construct()
    {
        $this->endpoint = config('services.paypal.endpoint');
        $this->middleware(['auth', 'verified']);
        $this->middleware('not-subscribed')->except('success');
    }

    public function checkout(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'id' => ['required', 'integer', 'exists:plans'],
        ]);

        $user = $request->user();
        $plan = Plan::find($request->id);

        // create the subscription
        $response = Http::withToken(Paypal::getAccessToken())
            ->acceptJson()
            ->withBody(json_encode([
                'plan_id' => $plan->paypal_id,
                'start_time' => now()->addSeconds(30)->toIso8601String(),
                'subscriber' => [
                    'email_address' => $user->email,
                ],
                'application_context' => [
                    'brand_name' => config('app.name'),
                    'locale' => 'en-US',
                    'user_action' => 'SUBSCRIBE_NOW',
                    'payment_method' => [
                        'payer_selected' => 'PAYPAL',
                        'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED'
                    ],
                    'return_url' => route('paypal.success'),
                    'cancel_url' => route('paypal.cancel'),
                ],
            ]), 'application/json')
            ->post("$this->endpoint/v1/billing/subscriptions");

        if ($response->status() !== 201) {
            return redirect()
                ->route('home.subscription')
                ->with('error', 'Unable to create PayPal subscription.');
        }

        // storing the PayPal session for 1 hour
        Cache::put($response->json('id'), [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
        ], now()->addHour());

        return redirect()->away($response->json('links.0.href'));
    }

    public function success(Request $request): RedirectResponse
    {
        // missing url parameters
        if (!$request->has(['subscription_id', 'ba_token', 'token'])) {
            return $this->failed();
        }

        // get the PayPal subscription_id from the url
        $subId = $request->get('subscription_id');

        // get the user_id, plan_id related to the PayPal subscription_id
        ['user_id' => $userId, 'plan_id' => $planID] = Cache::get($subId);

        // create pending subscription for the user
        $subscription = Subscription::create([
            'user_id' => $userId,
            'plan_id' => $planID,
            'paypal_id' => $subId,
            'status' => \App\Enums\Subscription::PENDING,
        ]);

        // broadcast new subscription event
        event(new SubscriptionCreatedEvent($subscription));

        return $this->successful();
    }

    public function cancel(): RedirectResponse
    {
        return $this->canceled();
    }
}
