<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property-read integer id
 *
 * @property string name
 * @property string description
 * @property integer price
 * @property integer cad_price
 * @property string paypal_id
 * @property string stripe_id
 * @property integer config_limit
 * @property boolean beta_access
 * @property boolean capes_access
 *
 * @property-read Carbon created_at
 * @property-read Carbon updated_at
 *
 * @property-read Collection subscriptions
 */
class Plan extends Model
{
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
