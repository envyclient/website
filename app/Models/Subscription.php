<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int user_id
 * @property int|null plan_id
 * @property int|null billing_agreement_id
 * @property Carbon|null end_date
 */
class Subscription extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'plan_id',
        'billing_agreement_id',
        'end_date',
    ];

    protected $casts = [
        'end_date' => 'datetime'
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
        return $this->belongsTo(BillingAgreement::class, 'id', 'billing_agreement_id');
    }
}
