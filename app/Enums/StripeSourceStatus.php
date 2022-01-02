<?php

namespace App\Enums;

enum StripeSourceStatus: string
{
    case PENDING = 'pending';
    case CANCELED = 'canceled';
    case FAILED = 'failed';
    case CHARGEABLE = 'chargeable';
    case SUCCEEDED = 'succeeded';
}
