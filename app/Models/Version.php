<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string name
 * @property boolean beta
 * @property string version
 * @property string assets
 * @property string changelog
 */
class Version extends Model
{
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
