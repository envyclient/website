<?php

namespace App\Enums;

enum LicenseRequest: string
{
    case PENDING = 'pending';
    case DENIED = 'denied';
    case APPROVED = 'approved';
}
