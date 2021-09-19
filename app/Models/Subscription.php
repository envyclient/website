<?php

namespace App\Models;

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

    const PENDING = 'Pending';
    const ACTIVE = 'Active';
    const CANCELED = 'Cancelled';

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
