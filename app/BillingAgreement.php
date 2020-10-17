<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int user_id
 * @property int plan_id
 * @property string billing_agreement_id
 * @property string state
 */
class BillingAgreement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'plan_id',
        'billing_agreement_id',
        'state',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function plan()
    {
        return $this->belongsTo('App\Plan');
    }

    public function subscription()
    {
        return $this->hasOne('App\Subscription', 'billing_agreement_id');
    }
}
