<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string name
 * @property string description
 * @property int price
 * @property int paypal_id
 * @property int config_limit
 * @property bool beta_access
 * @property bool capes_access
 */
class Plan extends Model
{
    protected $casts = [
        'beta_access' => 'bool',
        'capes_access' => 'bool',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
