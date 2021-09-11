<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read integer id
 *
 * @property integer user_id
 * @property integer subscription_id
 * @property string method
 * @property integer price
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

    protected $casts = [
        'user_id' => 'integer',
        'subscription_id' => 'integer',
        'price' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
