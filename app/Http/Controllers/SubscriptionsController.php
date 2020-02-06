<?php

namespace App\Http\Controllers;

use App\Notifications\Generic;
use App\Plan;
use App\Rules\PlanExists;
use App\Subscription;
use App\Util\AAL;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'aal_name']);
    }

    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'plan' => ['required', 'int', new PlanExists]
        ]);

        $user = auth()->user();
        $plan = Plan::find($request->plan);

        if ($user->hasSubscription()) {
            return back()->with('error', 'You are already subscribed to a subscription. You must let that one expire before you subscribe to a new one.');
        }

        $price = $plan->price;
        if ($user->canWithdraw($price)) {

            $code = AAL::addUser($user);
            switch ($code) {
                case 409:
                {
                    return back()->with('error', 'You already own the app. Please wait till the end of the day to subscribe again.');
                    break;
                }
                case 404:
                {
                    return back()->with('error', 'Your account does not exist on AAL. Please make sure you entered your name correctly.');
                    break;
                }
                case 403:
                {
                    return back()->with('error', 'An error has occurred. Please contact support.');
                    break;
                }
                case 400:
                {
                    return back()->with('error', 'App user limit has been reached. Please inform staff of this.');
                    break;
                }
            }

            // TODO: send email about new subscription

            $subscription = Subscription::firstOrNew(['user_id' => $user->id]);
            $subscription->plan_id = $plan->id;
            $subscription->end_date = Carbon::now()->addDays($plan->interval);
            $subscription->save();

            $user->withdraw($price, ['description' => 'Subscribed to plan ' . $plan->title . '.']);
            return back()->with('success', 'Plan purchased.');
        } else {
            return back()->with('error', 'You do not have enough credits.');
        }
    }

    public function cancel(Request $request)
    {
        $user = auth()->user();
        if (!$user->hasSubscription()) {
            return back()->with('error', 'You must subscribe to a plan first.');
        }
        $subscription = $user->subscription;
        $subscription->renew = false;
        $subscription->save();

        $this->notify(new Generic($user, 'You have cancelled your subscription and it will not renew.'));

        return back()->with('success', 'Your subscription has been cancelled and will not renew.');
    }
}
