<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

class DiscordAccountConnected
{
    use SerializesModels;

    public function __construct(
        public User $user,
    )
    {
    }
}
