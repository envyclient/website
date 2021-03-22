<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read int id
 *
 * @property int stripe_source_id
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

    public function source()
    {
        return $this->belongsTo(StripeSource::class);
    }
}
