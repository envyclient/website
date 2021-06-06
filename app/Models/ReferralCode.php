<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read int id
 *
 * @property int user_id
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
    protected $fillable = [
        'user_id',
        'code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function subscriptions()
    {
        return $this->hasManyThrough(Subscription::class, User::class, 'referral_code_id', 'user_id', 'id', 'id');
    }

    public function invoices()
    {
        return $this->hasManyThrough(Invoice::class, User::class, 'referral_code_id', 'user_id', 'id', 'id');
    }
}
