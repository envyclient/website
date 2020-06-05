<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelFavorite\Traits\Favoriteable;

/**
 * @property int user_id
 * @property string name
 * @property string data
 * @property boolean public
 */
class Config extends Model
{
    use Favoriteable;

    const PAGE_LIMIT = 12;

    protected $casts = [
        'data' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
