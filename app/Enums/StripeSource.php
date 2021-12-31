<?php

namespace App\Enums;

enum StripeSource: string
{
    case PENDING = 'pending';
    case CANCELED = 'canceled';
    case FAILED = 'failed';
    case CHARGEABLE = 'chargeable';
    case SUCCEEDED = 'succeeded';
}
