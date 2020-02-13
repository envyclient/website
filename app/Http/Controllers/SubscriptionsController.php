<?php

namespace App\Http\Controllers;

use App\Notifications\Generic;
use App\Plan;
use App\Rules\PlanExists;
use App\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'aal_name', 'forbid-banned-user']);
    }

    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'id' => ['required', 'int', new PlanExists]
        ]);

        $user = auth()->user();
        if ($user->hasSubscription()) {
            return back()->with('error', 'You are already subscribed to a subscription. You must let that one expire before you subscribe to a new one.');
        }

        $plan = Plan::find($request->id);
        $price = $plan->price;

        if (!$user->canWithdraw($price)) {
            return back()->with('error', 'You do not have enough credits.');
        }

        // TODO: send email about new subscription
        $subscription = new Subscription();
        $subscription->user_id = $user->id;
        $subscription->plan_id = $plan->id;
        $subscription->end_date = Carbon::now()->addDays($plan->interval);
        $subscription->save();

        $user->withdraw($price, 'withdraw', ['plan_id' => $plan->id, 'description' => "Subscribed to plan {$plan->name}."]);

        return back()->with('success', 'Successfully subscribed to plan.');
    }

    public function cancel(Request $request)
    {
        $user = auth()->user();

        if (!$user->hasSubscription()) {
            return back()->with('error', 'You must subscribe to a plan first.');
        }

        $user->subscription->fill([
            'renew' => false
        ])->save();

        $user->notify(new Generic($user, 'You have cancelled your subscription and it will not renew.'));

        return back()->with('success', 'Your subscription has been cancelled and will not renew.');
    }
}
