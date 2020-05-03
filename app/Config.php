<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelFavorite\Traits\Favoriteable;

/**
 * @property string name
 * @property json data
 * @property boolean public
 */
class Config extends Model
{
    use Favoriteable;

    const PAGE_LIMIT = 9;

    protected $casts = [
        'data' => 'json'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
