<?php

namespace App\Events\Subscription;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiredEvent
{
    use SerializesModels;

    public User $user;

    public function __construct(
        public Subscription $subscription,
    ) {
        $this->user = $this->subscription->user;
    }
}
