<?php

namespace App\Http\Controllers\Coinbase;

use App\Http\Controllers\Controller;
use App\Models\Coinbase;
use App\Models\Plan;
use App\Providers\RouteServiceProvider;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CoinbaseController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
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

        $coinbase = null;
        try {
            $coinbase = Coinbase::where([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => 'charge:created'
            ])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $response = Http::withHeaders([
                'X-CC-Api-Key' => 'e10d0ace-3376-4dea-ab0b-801df1a9b186',
                'X-CC-Version' => '2018-03-22'
            ])->withBody(json_encode([
                'name' => "Envy Client - $plan->name",
                'description' => $plan->description,
                'local_price' => [
                    'amount' => $plan->price,
                    'currency' => 'USD',
                ],
                'pricing_type' => 'fixed_price',
                'metadata' => [
                    'customer_id' => $user->id,
                    'customer_name' => $user->name
                ],
                //"redirect_url" => "https://charge/completed/page",
                'cancel_url' => route('coinbase.cancel'),
            ]), 'application/json')
                ->post('https://api.commerce.coinbase.com/charges/');

            if ($response->status() !== 201) {
                return back()->with('error', 'Could not initialize payment. Please try again later.');
            }

            $coinbase = Coinbase::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'charge_id' => $response->json('data.id'),
                'code' => $response->json('data.code'),
                'status' => 'charge:created',
            ]);
        }

        return redirect()->away("https://commerce.coinbase.com/charges/$coinbase->code");
    }

    public function cancel()
    {
        return redirect(RouteServiceProvider::SUBSCRIPTIONS)->with('error', 'Payment cancelled.');
    }
}
