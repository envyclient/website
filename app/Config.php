<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelFavorite\Traits\Favoriteable;

/**
 * @property string title
 * @property json data
 * @property boolean public
 */
class Config extends Model
{
    use Favoriteable;

    protected $casts = [
        'data' => 'json'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
