<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Overtrue\LaravelFavorite\Traits\Favoriteable;

/**
 * @property-read integer id
 *
 * @property integer user_id
 * @property string name
 * @property array data
 * @property boolean public
 * @property boolean official
 * @property integer version_id
 *
 * @property-read Carbon created_at
 * @property-read Carbon updated_at
 *
 * @property-read User user
 * @property-read Version version
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
        'user_id' => 'integer',
        'data' => 'array',
        'public' => 'boolean',
        'official' => 'boolean',
        'version_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class)->withTrashed();
    }
}
