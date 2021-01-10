<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int stripe_source_id
 * @property string type
 * @property string message
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
