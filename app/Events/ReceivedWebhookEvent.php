<?php

namespace App\Events;

use App\Enums\PaymentProvider;
use Illuminate\Queue\SerializesModels;

class ReceivedWebhookEvent
{
    use SerializesModels;

    public function __construct(
        public PaymentProvider $provider,
        public string          $type
    )
    {
    }
}
