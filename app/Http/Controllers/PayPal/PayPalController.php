<?php

namespace App\Http\Controllers\PayPal;

use App\Helpers\Paypal;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Traits\Payment;
use Illuminate\Http\Request;
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

    public function checkout(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|exists:plans',
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

        // storing the plan_id, so we can retrieve it when the user returns
        session([
            'plan_id' => $request->id
        ]);

        return redirect()->away($response->json('links.0.href'));
    }

    public function success(Request $request)
    {
        // getting the plan id from the session
        $planId = session('plan_id');
        session()->forget('plan_id');

        // missing url parameters
        if (!$request->has(['subscription_id', 'ba_token', 'token'])) {
            return $this->failed();
        }

        // create pending subscription for the user
        Subscription::create([
            'user_id' => auth()->id(),
            'plan_id' => $planId,
            'paypal_id' => $request->get('subscription_id'),
            'status' => Subscription::PENDING,
        ]);

        return $this->successful();
    }

    public function cancel()
    {
        return $this->canceled();
    }
}
