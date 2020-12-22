<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function creating(User $user)
    {
        $user->api_token = bin2hex(openssl_random_pseudo_bytes(30));
        $user->image = 'https://avatar.tobi.sh/avatar/' . md5(strtolower(trim($user->email))) . '.svg?text=' . strtoupper(substr($user->name, 0, 2));
    }
}
