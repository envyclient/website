<?php

namespace App\Http\Controllers;

use App\Plan;
use App\Subscription;
use App\User;
use Illuminate\Http\Request;

class PlansController extends Controller
{
    public function __construct()
    {
        $this->middleware(['verified', 'auth']);
    }

    public function purchase(Request $request, Plan $plan)
    {
        $user = auth()->user();
        $price = $plan->price;
        if ($user->canWithdraw($price)) {
            $user->withdraw($price);
            return back()->with('error', 'Plan purchased.');
        } else {
            return back()->with('error', 'You do not have enough credits.');
        }
    }

    public function cancel(Request $request)
    {
        $subscription = Subscription::where('user_id', auth()->id());
        $subscription->renew = false;
        $subscription->save();
    }
}
