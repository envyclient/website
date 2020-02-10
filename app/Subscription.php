<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id', 'plan_id', 'renew', 'end_date'
    ];

    protected $casts = [
        'end_date' => 'datetime:Y-m-d',
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
