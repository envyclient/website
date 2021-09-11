<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read integer id
 *
 * @property integer stripe_source_id
 * @property string type
 * @property string message
 *
 * @property-read Carbon created_at
 * @property-read Carbon updated_at
 *
 * @property-read StripeSource source
 */
class StripeSourceEvent extends Model
{
    protected $fillable = [
        'stripe_source_id',
        'type',
        'message',
    ];

    protected $casts = [
        'stripe_source_id' => 'integer',
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(StripeSource::class);
    }
}
