<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string title
 * @property json data
 * @property boolean public
 */
class Config extends Model
{
    protected $casts = [
        'data' => 'json'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
