<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CancelSubscription extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function __invoke(Request $request)
    {
        // TODO
        /* $user = $request->user();

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

         return back()->with('success', 'Your subscription has been cancelled and will not renew.');*/
    }
}
