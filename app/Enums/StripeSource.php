<?php

namespace App\Enums;

final class StripeSource
{
    const PENDING = 'pending';
    const CANCELED = 'canceled';
    const FAILED = 'failed';
    const CHARGEABLE = 'chargeable';
    const SUCCEEDED = 'succeeded';

    private function __construct()
    {
    }
}
