<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int id
 * @property int user_id
 * @property string channel
 * @property string channel_name
 * @property string channel_image
 * @property string status
 * @property string|null action_reason
 * @property Carbon|null action_at
 * @property-read Carbon created_at
 * @property-read Carbon updated_at
 * @property-read User user
 */
class LicenseRequest extends Model
{
    const DAYS_TO_ADD = 3;

    const SUBSCRIBER_REQUIREMENT = 200;

    protected $fillable = [
        'user_id',
        'channel',
        'channel_name',
        'channel_image',
        'status',
        'action_reason',
        'action_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'action_at' => 'datetime',
    ];

    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $status === 'all'
            ? $query
            : $query->where('status', $status);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
