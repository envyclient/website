<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int user_id
 * @property int plan_id
 * @property int|null billing_agreement_id
 * @property string|null stripe_id
 * @property string|null stripe_status
 * @property Carbon end_date
 *
 * @property User user
 * @property Plan plan
 * @property BillingAgreement billingAgreement
 */
class Subscription extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'plan_id',
        'billing_agreement_id',
        'stripe_id',
        'stripe_status',
        'end_date',
    ];

    protected $dates = [
        'end_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function billingAgreement()
    {
        return $this->belongsTo(BillingAgreement::class, 'billing_agreement_id');
    }
}
