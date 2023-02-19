<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function __invoke(Request $request, string $id, string $hash): RedirectResponse
    {
        $user = $request->user();

        if (! hash_equals($id, (string) $user->getKey())) {
            throw new AuthorizationException();
        }

        if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException();
        }

        if ($user->hasVerifiedEmail()) {
            return redirect(route('home'));
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect(route('home'));
    }
}
