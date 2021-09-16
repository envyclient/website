<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Sushi\Sushi;

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
    use Sushi;

    public $timestamps = false;

    protected $casts = [
        'price' => 'integer',
        'cad_price' => 'integer',
        'config_limit' => 'integer',
        'beta_access' => 'bool',
        'capes_access' => 'bool',
    ];

    public function getRows(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Free',
                'description' => 'Get free access to Envy Client for 30 days.',
                'price' => 0,
                'cad_price' => 0,
                'paypal_id' => null,
                'stripe_id' => null,
                'config_limit' => 15,
                'beta_access' => true,
                'capes_access' => true,
            ],
            [
                'id' => 2,
                'name' => 'Standard',
                'description' => 'Get standard access to Envy Client for 30 days.',
                'price' => 7,
                'cad_price' => 900,
                'paypal_id' => config('services.paypal.plans.standard'),
                'stripe_id' => config('services.stripe.plans.standard'),
                'config_limit' => 5,
                'beta_access' => false,
                'capes_access' => false,
            ],
            [
                'id' => 3,
                'name' => 'Premium',
                'description' => 'Get premium access to Envy Client for 30 days.',
                'price' => 10,
                'cad_price' => 1300,
                'paypal_id' => config('services.paypal.plans.premium'),
                'stripe_id' => config('services.stripe.plans.premium'),
                'config_limit' => 15,
                'beta_access' => true,
                'capes_access' => true,
            ],
        ];
    }

    protected function sushiShouldCache(): bool
    {
        return true;
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
