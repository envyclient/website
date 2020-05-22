<?php

namespace App;

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
        return $this->belongsToMany('App\User', 'user_downloads', 'version_id', 'user_id');
    }
}
