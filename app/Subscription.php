<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int user_id
 * @property int plan_id
 * @property string end_date
 */
class Subscription extends Model
{
    use SoftDeletes;
    
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
