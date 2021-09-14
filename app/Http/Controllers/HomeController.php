<?php

namespace App\Http\Controllers;

use App\Models\Plan;

class HomeController extends Controller
{
    public function home()
    {
        $user = auth()
            ->user()
            ->load(['subscription', 'configs', 'licenseRequests']);

        return view('pages.dashboard.home', compact('user'));
    }

    public function profile()
    {
        $user = auth()
            ->user()
            ->load(['subscription', 'billingAgreement']);

        return view('pages.dashboard.profile', compact('user'));
    }

    public function subscription()
    {
        $user = auth()
            ->user()
            ->load(['subscription.plan', 'billingAgreement.plan']);

        // get all the non-free plans
        $plans = Plan::where('price', '<>', 0)->get();

        return view('pages.dashboard.subscriptions', compact('user', 'plans'));
    }
}
