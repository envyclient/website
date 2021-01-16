<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PagesController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function dashboard(Request $request)
    {
        $user = $request->user()
            ->load(['subscription', 'configs']);

        return view('pages.dashboard.home', [
            'user' => $user,
            'configs' => $user->configs()->withCount('favorites')->orderBy('updated_at', 'desc')->get(),
        ]);
    }

    public function security(Request $request)
    {
        $user = $request->user()
            ->load(['subscription', 'billingAgreement']);

        return view('pages.dashboard.security')
            ->with('user', $user);
    }

    public function subscriptions(Request $request)
    {
        $user = $request->user();
        return view('pages.dashboard.subscriptions', [
            'user' => $user,
            'plans' => Plan::where('price', '<>', 0)->get(),
            'nextSubscription' => $user->hasSubscription() ? $user->subscription->end_date->diffInDays() : null
        ]);
    }

    public function discord(Request $request)
    {
        return view('pages.dashboard.discord', [
            'user' => $request->user(),
        ]);
    }
}
