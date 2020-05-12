<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property  string name
 * @property  string file
 */
class Download extends Model
{
    const FILES_DIRECTORY = 'downloads';
}
