<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property-read integer id
 *
 * @property integer user_id
 * @property integer plan_id
 * @property string source_id
 * @property string client_secret
 * @property string status // pending, canceled, failed, chargeable, succeeded
 * @property string url
 *
 * @property-read Carbon created_at
 * @property-read Carbon updated_at
 *
 * @property-read User user
 * @property-read Plan plan
 * @property-read Collection events
 */
class StripeSource extends Model
{
    use Prunable;

    const PENDING = 'pending';
    const CANCELED = 'canceled';
    const FAILED = 'failed';
    const CHARGEABLE = 'chargeable';
    const SUCCEEDED = 'succeeded';

    protected $fillable = [
        'user_id',
        'plan_id',
        'source_id',
        'client_secret',
        'status',
        'url',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'plan_id' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(fn(StripeSource $source) => $source->events()->delete());
    }

    public function prunable()
    {
        return static::where('status', self::CANCELED)->orWhere('status', self::FAILED);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(StripeSourceEvent::class);
    }
}
