<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property  string name
 * @property  boolean beta
 * @property  string file
 */
class Version extends Model
{
    const FILES_DIRECTORY = 'versions';

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_downloads', 'version_id', 'user_id');
    }
}
