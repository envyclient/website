<?php

namespace App\Models;

use App\Listeners\SubscriptionSubscriber;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * @property-read integer id
 *
 * @property integer user_id
 * @property string code
 *
 * @property-read Carbon created_at
 * @property-read Carbon updated_at
 *
 * @property-read User user
 * @property-read Collection users
 * @property-read Collection subscriptions
 * @property-read Collection invoices
 */
class ReferralCode extends Model
{
    use HasFactory;
    use HasRelationships;

    protected $fillable = [
        'user_id',
        'code',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function subscriptions(): HasManyThrough
    {
        return $this->hasManyThrough(Subscription::class, User::class, 'referral_code_id', 'user_id', 'id', 'id');
    }

    public function invoices(): HasManyThrough
    {
        return $this->hasManyDeep(
            Invoice::class,
            [User::class, Subscription::class],
            ['id', 'id', 'subscription_id'],
            ['id', 'referral_code_id', 'id'],
        );
    }
}
