<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read int id
 *
 * @property int user_id
 * @property string name
 * @property string channel
 * @property string channel_name
 * @property string channel_image
 * @property string status
 * @property string|null action_reason
 * @property Carbon|null action_at
 *
 * @property-read Carbon created_at
 * @property-read Carbon updated_at
 *
 * @property-read User user
 */
class LicenseRequest extends Model
{
    const PENDING = 'pending';
    const DENIED = 'denied';
    const APPROVED = 'approved';
    const EXTENDED = 'extended';

    protected $fillable = [
        'user_id',
        'channel',
        'channel_name',
        'channel_image',
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
