<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Support\Carbon;

/**
 * @property-read int id
 *
 * @property int user_id
 * @property int plan_id
 * @property int stripe_session_id
 * @property Carbon|null completed_at
 *
 * @property-read Carbon created_at
 * @property-read Carbon updated_at
 *
 * @property-read User user
 * @property-read Plan plan
 */
class StripeSession extends Model
{
    use Prunable;

    protected $fillable = [
        'user_id',
        'plan_id',
        'stripe_session_id',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function prunable()
    {
        return static::whereNull('completed_at')
            ->where('created_at', '<', now()->subDay());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
