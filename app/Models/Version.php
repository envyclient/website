<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read int id
 *
 * @property string name
 * @property boolean beta
 * @property string version
 * @property string assets
 * @property string changelog
 *
 * @property-read Carbon created_at
 * @property-read Carbon updated_at
 *
 * @property-read Collection users
 */
class Version extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'beta',
        'version',
        'assets',
        'changelog',
    ];

    protected $hidden = [
        'version',
        'assets',
    ];

    protected $casts = [
        'beta' => 'bool',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_downloads', 'version_id', 'user_id');
    }
}
