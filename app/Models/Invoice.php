<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read int id
 *
 * @property int user_id
 * @property int subscription_id
 * @property string method
 * @property int price
 *
 * @property-read Carbon created_at
 * @property-read Carbon updated_at
 *
 * @property-read User user
 * @property-read Subscription subscription
 */
class Invoice extends Model
{
    const STRIPE = 'stripe';
    const PAYPAL = 'paypal';
    const WECHAT = 'wechat';

    protected $fillable = [
        'user_id',
        'subscription_id',
        'method',
        'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
