<?php

namespace App\Http\Controllers\PayPal;

use App\Helpers\Paypal;
use App\Http\Controllers\Controller;
use App\Models\BillingAgreement;
use App\Models\Plan;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PayPalController extends Controller
{
    private $endpoint;

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->endpoint = config('paypal.endpoint');
    }

    public function process(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|exists:plans'
        ]);

        $user = $request->user();
        if ($user->hasBillingAgreement()) {
            return back()->with('error', 'You already have a active subscription.');
        }

        $response = Http::withToken(Paypal::getAccessToken())
            ->withBody(json_encode([
                'name' => 'Base Agreement',
                'description' => 'Basic Agreement',
                'start_date' => today()->addMonth()->toIso8601String(),
                'plan' => [
                    'id' => Plan::find($request->id)->paypal_id
                ],
                'payer' => [
                    'payment_method' => 'paypal'
                ]
            ]), 'application/json')
            ->post("$this->endpoint/v1/payments/billing-agreements/");

        if ($response->status() !== 201) {
            return redirect(RouteServiceProvider::HOME)->with('error', 'Subscription failed.');
        }

        // storing the plan id so we can retrieve it when the user returns
        session([
            'plan_id' => $request->id
        ]);

        return redirect()->away($response->json()['links'][0]['href']);
    }

    public function execute(Request $request)
    {
        // getting the plan id from the session
        $planId = session('plan_id');
        session()->forget('plan_id');

        if (!$request->has('token')) {
            return redirect(RouteServiceProvider::HOME)->with('error', 'Subscription failed.');
        }

        $user = $request->user();
        if ($user->hasSubscription()) {
            return back()->with('error', 'You already have a active subscription.');
        }

        $response = HTTP::withToken(Paypal::getAccessToken())
            ->withHeaders([
                'Content-Type:' => 'application/json'
            ])
            ->post("$this->endpoint/v1/payments/billing-agreements/$request->token/agreement-execute");

        if ($response->status() !== 200) {
            return redirect(RouteServiceProvider::HOME)->with('error', 'Subscription failed.');
        }

        $responseData = $response->json();
        if ($responseData['state'] !== 'Active') {
            return redirect(RouteServiceProvider::HOME)->with('error', 'Subscription failed.');
        }

        BillingAgreement::create([
            'user_id' => $request->user()->id,
            'plan_id' => $planId,
            'billing_agreement_id' => $responseData['id'],
            'state' => $responseData['state'],
        ]);

        return redirect(RouteServiceProvider::SUBSCRIPTIONS)->with('success', 'Subscription successful.');
    }

    public function cancel()
    {
        return redirect(RouteServiceProvider::SUBSCRIPTIONS)->with('error', 'Subscription cancelled.');
    }

}
