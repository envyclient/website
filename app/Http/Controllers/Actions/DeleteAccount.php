<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeleteAccount extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        return $request->has('from_admin')
            ? self::deleteUser(User::find($request->input('user_id')))
            : self::deleteUser(auth()->user());
    }

    private static function deleteUser(User $user)
    {
        // if the user has a subscription we redirect them back
        if ($user->hasSubscription()) {
            return back()->with('error', 'You cannot delete your account if you have an active subscription.');
        }

        // if the user has ever had a subscription before
        if ($user->subscription()->withTrashed()->exists()) {
            return back()->with('error', 'You cannot delete your account if you have had a subscription.');
        }

        // delete favorites
        DB::table('favorites')->where('user_id', $user->id)->delete();

        // delete configs
        $user->configs()->delete();

        // delete invoices
        Invoice::where('user_id', $user->id)->delete();

        // delete subscriptions
        Subscription::where('user_id', $user->id)->delete();

        // delete user_downloads
        DB::table('user_downloads')->where('user_id', $user->id)->delete();

        // delete license_requests
        $user->licenseRequests()->delete();

        // delete password_resets
        DB::table('password_resets')->where('email', $user->email)->delete();

        // delete the user
        $user->delete();

        // log the user out
        auth()->logout();
        return redirect('/');
    }
}
