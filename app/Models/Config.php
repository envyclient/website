<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    use Favoriteable, HasFactory;

    const PAGE_LIMIT = 5;

    protected $fillable = [
        'user_id',
        'name',
        'data',
        'public',
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
