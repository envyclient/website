<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string name
 * @property boolean beta
 * @property string file
 */
class Version extends Model
{
    protected $fillable = [
        'name',
        'beta',
        'file',
        'improvements',
        'removed',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_downloads', 'version_id', 'user_id');
    }
}
