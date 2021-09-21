<?php

namespace App\Policies;

use App\Models\Config;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConfigPolicy
{
    use HandlesAuthorization;

    public function favorite(User $user, Config $config): bool
    {
        return $user->id !== $config->user_id;
    }
}
