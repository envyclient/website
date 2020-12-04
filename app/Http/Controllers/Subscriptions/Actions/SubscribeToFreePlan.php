<?php

namespace App\Http\Controllers\Subscriptions\Actions;

use App\Http\Controllers\Subscriptions\SubscriptionsController;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubscribeToFreePlan extends SubscriptionsController
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if (!$user->access_free_plan) {
            return back();
        }

        if ($user->hasSubscription()) {
            return back()->with('error', 'You already have a subscription.');
        }

        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => 1,
            'end_date' => Carbon::now()->addDecade(),
        ]);

        /*$user->notify(new SubscriptionUpdated(
            'New Subscription',
            'Thank you for subscribing to the free plan.'
        ));*/

        return back()->with('success', 'Subscribed to the free plan.');
    }
}
