<?php

namespace App\Observers;

use App\Models\ReferralCode;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;

class UserObserver
{
    public function creating(User $user)
    {
        $user->api_token = bin2hex(openssl_random_pseudo_bytes(30));
        $user->cape = asset('assets/capes/default.png');

        // handle referral code cookie
        if (request()->hasCookie('referral') && ReferralCode::where('code', request()->cookie('referral'))->exists()) {
            $code = ReferralCode::where('code', request()->cookie('referral'))->first();
            $user->referral_code_id = $code->id;
            $user->referral_code_used_at = now();

            // forgetting the referral cookie
            Cookie::queue(Cookie::forget('referral'));
        }
    }
}
