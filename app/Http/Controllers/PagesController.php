<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Plan;

class PagesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('admin')->only('notifications', 'sales');
    }

    public function dashboard()
    {
        $user = auth()->user()
            ->load(['subscription', 'configs']);

        return view('pages.dashboard.home', [
            'user' => $user,
            'configs' => $user->configs()
                ->withCount('favorites')
                ->orderBy('updated_at', 'desc')
                ->get(),
        ]);
    }

    public function security()
    {
        $user = auth()->user()
            ->load(['subscription', 'billingAgreement']);

        return view('pages.dashboard.security')
            ->with('user', $user);
    }

    public function subscriptions()
    {
        $user = auth()->user();
        return view('pages.dashboard.subscriptions', [
            'user' => $user,
            'plans' => Plan::where('price', '<>', 0)->get(),
            'nextSubscription' => $user->hasSubscription() ? now()->diffInDays($user->subscription->end_date, false) : null
        ]);
    }

    public function discord()
    {
        return view('pages.dashboard.discord')
            ->with('user', auth()->user());
    }

    public function notifications()
    {
        $notifications = auth()->user()
            ->notifications()
            ->paginate(10);

        return view('pages.dashboard.admin.notifications', [
            'notifications' => $notifications,
        ]);
    }

    public function sales()
    {
        return view('pages.dashboard.admin.sales', [
            'total' => Invoice::sum('price'),
            'methods' => [
                'paypal' => Invoice::where('method', 'paypal')->sum('price'),
                'wechat' => Invoice::where('method', 'wechat')->sum('price'),
                'crypto' => Invoice::where('method', 'crypto')->sum('price'),
                'stripe' => Invoice::where('method', 'stripe')->sum('price'),
            ],
        ]);
    }
}
