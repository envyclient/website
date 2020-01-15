<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const DEFAULT = [1, 'Default'];
    const PREMIUM = [2, 'Premium'];
    const ADMIN = [3, 'Admin'];
}
