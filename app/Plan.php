<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    public function subscriptions()
    {
        return $this->belongsToMany('App\Subscription');
    }
}
