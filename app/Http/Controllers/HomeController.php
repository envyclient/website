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


        return view('pages.dashboard.home', [
            'user' => $user,
            'configs' => $user->configs()
                ->withCount('favorites')
                ->orderBy('updated_at', 'desc')
                ->get(),
        ]);
    }

    public function profile()
    {
        $user = auth()
            ->user()
            ->load(['subscription', 'billingAgreement']);

        return view('pages.dashboard.profile')
            ->with('user', $user);
    }

    public function subscription()
    {
        $user = auth()
            ->user()
            ->load(['subscription.plan', 'billingAgreement.plan']);

        return view('pages.dashboard.subscriptions', [
            'user' => $user,
            'plans' => Plan::where('price', '<>', 0)->get(),
        ]);
    }
}
