<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Overtrue\LaravelFavorite\Traits\Favoriteable;

/**
 * @property-read int id
 * @property int user_id
 * @property int version_id
 * @property string name
 * @property array data
 * @property bool public
 * @property bool official
 * @property-read Carbon created_at
 * @property-read Carbon updated_at
 * @property-read User user
 * @property-read Version version
 */
class Config extends Model
{
    use Favoriteable;
    use HasFactory;

    const PAGE_LIMIT = 5;

    protected $fillable = [
        'user_id',
        'version_id',
        'name',
        'data',
        'public',
        'official',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'version_id' => 'integer',
        'data' => 'array',
        'public' => 'boolean',
        'official' => 'boolean',
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
