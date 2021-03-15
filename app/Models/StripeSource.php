<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int user_id
 * @property int plan_id
 * @property string source_id
 * @property string client_secret
 * @property string status // pending, canceled, failed, chargeable, succeeded
 * @property string url
 *
 * @property-read User user
 * @property-read Plan plan
 * @property-read Collection events
 */
class StripeSource extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'source_id',
        'client_secret',
        'status',
        'url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function events()
    {
        return $this->hasMany(StripeSourceEvent::class);
    }
}
