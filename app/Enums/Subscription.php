<?php

namespace App\Enums;

final class Subscription
{
    const PENDING = 'Pending';
    const ACTIVE = 'Active';
    const CANCELED = 'Cancelled';

    private function __construct()
    {
    }
}
