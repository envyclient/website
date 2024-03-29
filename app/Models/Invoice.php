<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int id
 * @property int subscription_id
 * @property string method
 * @property int price
 * @property-read Carbon created_at
 * @property-read Carbon updated_at
 * @property-read User user
 * @property-read Subscription subscription
 */
class Invoice extends Model
{
    protected $fillable = [
        'subscription_id',
        'method',
        'price',
    ];

    protected $casts = [
        'subscription_id' => 'integer',
        'price' => 'integer',
    ];

    public function user()
    {
        return $this->hasOneThrough(User::class, Subscription::class, 'user_id', 'id', 'id');
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
