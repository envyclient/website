<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string billing_agreement_id
 * @property int user_id
 * @property int plan_id
 */
class BillingAgreement extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function plan()
    {
        return $this->hasOne('App\Plan', 'id', 'plan_id');
    }
}
