<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const DEFAULT = 'Default';
    const PREMIUM = 'Premium';
    const ADMIN = 'Admin';
}
