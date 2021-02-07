<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int user_id
 * @property int subscription_id
 * @property string method
 * @property int price
 *
 * @property User user
 * @property Subscription subscription
 */
class Invoice extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_id',
        'method',
        'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
