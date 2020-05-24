<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int user_id
 * @property string code
 */
class ReferralCode extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function users()
    {
        return $this->hasMany('App\User', 'referral_code_id', 'id');
    }
}
