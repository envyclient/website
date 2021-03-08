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
 * @property boolean official
 * @property int version_id
 *
 * @property User user
 * @property Version version
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
        'official',
        'version_id',
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function version()
    {
        return $this->belongsTo(Version::class);
    }
}
