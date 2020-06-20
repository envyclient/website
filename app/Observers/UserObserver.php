<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    public function creating(User $user)
    {
        $user->api_token = bin2hex(openssl_random_pseudo_bytes(30));
    }

    public function created(User $user)
    {
        $user->wallet()->create();
        if ($user->referralCode()->exists()) {
            $referralCode = $user->referralCode;
            $user->deposit(2, 'deposit', ['referral_code_id' => $referralCode->id, 'description' => "Used referral code '{$referralCode->name}'."]);
        }
    }
}
