<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int user_id
 * @property string channel
 * @property string status
 * @property null|string action_reason
 * @property null|Carbon action_at
 *
 * @property-read User user
 */
class LicenseRequest extends Model
{
    protected $fillable = [
        'user_id',
        'channel',
        'status',
        'action_reason',
        'action_at',
    ];

    protected $dates = [
        'action_at'
    ];

    public function scopeStatus($query, $status)
    {
        if ($status !== 'all') {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
