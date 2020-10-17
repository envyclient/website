<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int user_id
 * @property int|null plan_id
 * @property int|null billing_agreement_id
 * @property bool renew
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
        'renew',
        'end_date',
    ];

    protected $casts = [
        'end_date' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function plan()
    {
        return $this->belongsTo('App\Plan');
    }

    public function billingAgreement()
    {
        return $this->belongsTo('App\BillingAgreement', 'id', 'billing_agreement_id');
    }
}
