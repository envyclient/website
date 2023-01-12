<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int id
 * @property string name
 * @property string description
 * @property int price
 * @property int cad_price
 * @property string paypal_id
 * @property string stripe_id
 * @property int config_limit
 * @property bool beta_access
 * @property bool capes_access
 * @property-read Collection subscriptions
 */
class Plan extends Model
{
    public $timestamps = false;

    protected $casts = [
        'price' => 'integer',
        'cad_price' => 'integer',
        'config_limit' => 'integer',
        'beta_access' => 'bool',
        'capes_access' => 'bool',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
