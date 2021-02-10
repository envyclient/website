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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
