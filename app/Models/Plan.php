<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read int id
 *
 * @property string name
 * @property string description
 * @property int price
 * @property int cad_price
 * @property int paypal_id
 * @property string stripe_id
 * @property int config_limit
 * @property bool beta_access
 * @property bool capes_access
 *
 * @property-read Carbon created_at
 * @property-read Carbon updated_at
 *
 * @property-read Collection subscriptions
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
