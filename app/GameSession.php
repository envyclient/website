<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int user_id
 * @property string data
 */
class GameSession extends Model
{
    protected $fillable = [
        'user_id', 'data'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
