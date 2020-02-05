<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelFollow\Traits\CanBeFavorited;

/**
 * @property string title
 * @property json data
 * @property boolean public
 */
class Config extends Model
{
    use CanBeFavorited;

    const LIMIT = 5;

    protected $casts = [
        'data' => 'json'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
