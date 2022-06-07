<?php

namespace App\Models;

use App\Enums\PaymentProvider;
use App\Events\Subscription\SubscriptionExpiredEvent;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property-read integer id
 *
 * @property integer user_id
 * @property integer plan_id
 * @property string|null paypal_id
 * @property string|null stripe_id
 * @property string status
 * @property Carbon end_date
 * @property boolean queued_for_cancellation
 *
 * @property-read PaymentProvider paymentProvider
 *
 * @property-read Carbon created_at
 * @property-read Carbon updated_at
 * @property-read Carbon deleted_at
 *
 * @property-read User user
 * @property-read Plan plan
 */
class Subscription extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'plan_id',
        'paypal_id',
        'stripe_id',
        'status',
        'end_date',
        'queued_for_cancellation',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'plan_id' => 'integer',
        'end_date' => 'datetime',
        'queued_for_cancellation' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function (Subscription $subscription) {
            event(new SubscriptionExpiredEvent($subscription));
        });
    }

    protected function paymentProvider(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['paypal_id'] !== null ? PaymentProvider::PAYPAL : PaymentProvider::STRIPE,
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
