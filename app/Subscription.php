<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int user_id
 * @property int plan_id
 * @property bool renew
 * @property string end_date
 */
class Subscription extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'plan_id', 'renew', 'end_date'
    ];

    protected $casts = [
        'end_date' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function plan()
    {
        return $this->hasOne('App\Plan', 'id', 'plan_id');
    }
}
