<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property  string name
 * @property  boolean beta
 * @property  string file
 */
class Version extends Model
{
    use SoftDeletes;

    const FILES_DIRECTORY = 'versions';

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_downloads', 'version_id', 'user_id');
    }
}
