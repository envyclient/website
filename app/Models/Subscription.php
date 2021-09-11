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
 * @property integer|null billing_agreement_id
 * @property string|null stripe_id
 * @property string|null stripe_status
 * @property Carbon end_date
 *
 * @property-read Carbon created_at
 * @property-read Carbon updated_at
 * @property-read Carbon deleted_at
 *
 * @property-read User user
 * @property-read Plan plan
 * @property-read BillingAgreement billingAgreement
 */
class Subscription extends Model
{
    use SoftDeletes;

    const ACTIVE = 'Active';
    const CANCELED = 'Cancelled';

    protected $fillable = [
        'user_id',
        'plan_id',
        'billing_agreement_id',
        'stripe_id',
        'stripe_status',
        'end_date',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'plan_id' => 'integer',
        'billing_agreement_id' => 'integer',
        'end_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function billingAgreement(): BelongsTo
    {
        return $this->belongsTo(BillingAgreement::class, 'billing_agreement_id');
    }
}
