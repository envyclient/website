<?php

namespace App\Http\Controllers;

use App\Notifications\Generic;
use App\Notifications\NewSubscription;
use App\Plan;
use App\Rules\PlanExists;
use App\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'forbid-banned-user']);
    }

    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'id' => ['required', 'int', new PlanExists]
        ]);

        $user = auth()->user();
        if ($user->hasSubscription()) {
            return back()->with('error', 'You are already subscribed to a plan. You must let that one expire before you subscribe to a new one.');
        }

        $plan = Plan::find($request->id);
        $price = $plan->price;

        if (!$user->canWithdraw($price)) {
            return back()->with('error', 'You do not have enough credits.');
        }


        $subscription = new Subscription();
        $subscription->user_id = $user->id;
        $subscription->plan_id = $plan->id;
        $subscription->end_date = Carbon::now()->addDays($plan->interval);
        $subscription->save();

        $user->withdraw($price, 'withdraw', ['plan_id' => $plan->id, 'description' => "Subscribed to plan {$plan->name}."]);

        // send email
        $user->notify(new NewSubscription($user));

        return back()->with('success', 'Successfully subscribed to plan.');
    }

    public function cancel(Request $request)
    {
        $user = auth()->user();

        if (!$user->hasSubscription()) {
            return back()->with('error', 'You must subscribe to a plan first.');
        }

        if (!$user->subscription->renew) {
            return back()->with('error', 'You have already cancelled your subscription.');
        }

        $user->subscription->fill([
            'renew' => false
        ])->save();

        $user->notify(new Generic($user, 'You have successfully cancelled your subscription, you will not be charged at the next billing cycle. ', 'Subscription'));

        return back()->with('success', 'Your subscription has been cancelled and will not renew.');
    }
}
