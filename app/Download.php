<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property  string name
 * @property  string file
 */
// TODO: rename to version, ability to remove version
class Download extends Model
{
    const FILES_DIRECTORY = 'downloads';
}
