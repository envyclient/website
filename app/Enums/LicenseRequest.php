<?php

namespace App\Enums;

final class LicenseRequest
{
    const PENDING = 'pending';
    const DENIED = 'denied';
    const APPROVED = 'approved';

    private function __construct()
    {
    }
}
