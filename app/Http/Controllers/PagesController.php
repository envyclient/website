<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;

class PagesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('admin')->only('users', 'notifications', 'sales');
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

    public function users()
    {
        return view('pages.dashboard.admin.users', [
            'users' => [
                'count' => User::count(),
                'active' => User::where('current_account', '<>', null)->count(),
            ],
            'today' => [
                'users' => User::whereDay('created_at', today())->count(),
                'subscriptions' => Subscription::whereDay('created_at', today())->count()
            ],
            'subscriptions' => [
                'free' => User::whereHas('subscription', function ($q) {
                    $q->where('plan_id', 1);
                })->count(),
                'standard' => User::whereHas('subscription', function ($q) {
                    $q->where('plan_id', 2);
                })->count(),
                'premium' => User::whereHas('subscription', function ($q) {
                    $q->where('plan_id', 3);
                })->count(),
            ],
        ]);
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
