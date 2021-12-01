<?php

namespace App\Enums;

enum Subscription: string
{
    case PENDING = 'Pending';
    case ACTIVE = 'Active';
    case CANCELED = 'Cancelled';
}
