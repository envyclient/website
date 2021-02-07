<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int user_id
 * @property int plan_id
 * @property int stripe_session_id
 *
 * @property User user
 * @property Plan plan
 */
class StripeSession extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'stripe_session_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
