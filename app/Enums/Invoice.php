<?php

namespace App\Enums;

enum Invoice: string
{
    case STRIPE = 'stripe';
    case PAYPAL = 'paypal';
    case WECHAT = 'wechat';
}
