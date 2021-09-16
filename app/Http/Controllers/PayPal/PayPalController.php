<?php

namespace App\Http\Controllers\PayPal;

use App\Helpers\Paypal;
use App\Http\Controllers\Controller;
use App\Models\BillingAgreement;
use App\Models\Plan;
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
    }

    public function process(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|exists:App\Models\Plan',
        ]);

        if ($request->user()->hasBillingAgreement()) {
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
            ->post("$this->endpoint/v1/payments/billing-agreements");

        if ($response->status() !== 201) {
            return redirect()
                ->route('home.subscription')
                ->with('error', 'Subscription failed.');
        }

        // storing the plan_id, so we can retrieve it when the user returns
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

        // missing token in url
        if (!$request->has('token')) {
            return $this->failed();
        }

        // attempt to execute the billing agreement
        $response = HTTP::withToken(Paypal::getAccessToken())
            ->contentType('application/json')
            ->post("$this->endpoint/v1/payments/billing-agreements/$request->token/agreement-execute");

        // could not execute billing agreement
        if ($response->failed()) {
            return self::failed();
        }

        $responseData = $response->json();

        // double-checking if the billing agreement is active
        if ($responseData['state'] !== 'Active') {
            return self::failed();
        }

        // create the billing agreement for the user
        BillingAgreement::create([
            'user_id' => $request->user()->id,
            'plan_id' => $planId,
            'billing_agreement_id' => $responseData['id'],
            'state' => $responseData['state'],
        ]);

        return $this->successful();
    }

    public function cancel()
    {
        return $this->canceled();
    }

}
