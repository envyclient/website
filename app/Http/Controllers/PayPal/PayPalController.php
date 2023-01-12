<?php

namespace App\Http\Controllers\PayPal;

use App\Enums\SubscriptionStatus;
use App\Events\Subscription\SubscriptionCreatedEvent;
use App\Helpers\PaypalHelper;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Traits\Payment;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PayPalController extends Controller
{
    use Payment;

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('not-subscribed')->except('success');
    }

    public function checkout(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'id' => ['required', 'integer', 'exists:plans'],
        ]);

        $user = $request->user();
        $plan = Plan::query()->findOrFail(
            $request->input('id')
        );

        // create the subscription
        try {
            $response = PaypalHelper::createSubscription($user, $plan);
        } catch (Exception) {
            return redirect()
                ->route('home.subscription')
                ->with('error', 'Unable to create PayPal subscription.');
        }

        // storing the PayPal session for 1 hour
        Cache::put($response->json('id'), [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
        ], now()->addHour());

        return redirect()->away(
            $response->json('links.0.href')
        );
    }

    public function success(Request $request): RedirectResponse
    {
        // missing url parameters
        if (! $request->has(['subscription_id', 'ba_token', 'token'])) {
            return $this->failed();
        }

        // get the PayPal subscription_id from the url
        $subscription_id = $request->get('subscription_id');

        // get the user_id, plan_id related to the PayPal subscription_id
        ['user_id' => $userId, 'plan_id' => $planID] = Cache::get($subscription_id);

        // create pending subscription for the user
        $subscription = Subscription::query()
            ->create([
                'user_id' => $userId,
                'plan_id' => $planID,
                'paypal_id' => $subscription_id,
                'status' => SubscriptionStatus::PENDING->value,
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
