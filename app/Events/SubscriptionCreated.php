<?php

namespace App\Events;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Queue\SerializesModels;

class SubscriptionCreated
{
    use SerializesModels;

    public User $user;

    public function __construct(
        public Subscription $subscription,
    )
    {
        $this->user = $this->subscription->user;
    }
}
