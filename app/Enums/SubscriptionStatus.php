<?php

namespace App\Enums;

enum SubscriptionStatus: string
{
    case PENDING = 'Pending';
    case ACTIVE = 'Active';
    case CANCELED = 'Cancelled';
}
