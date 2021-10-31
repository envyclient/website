<?php

namespace App\Enums;

final class Invoice
{
    const STRIPE = 'stripe';
    const PAYPAL = 'paypal';
    const WECHAT = 'wechat';

    private function __construct()
    {
    }
}
