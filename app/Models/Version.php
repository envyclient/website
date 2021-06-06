<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property-read int id
 *
 * @property string name
 * @property boolean beta
 * @property string changelog
 * @property string main_class
 *
 * @property-read Carbon created_at
 * @property-read Carbon updated_at
 * @property-read Carbon deleted_at
 *
 * @property-read Collection users
 */
class Version extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'beta',
        'changelog',
        'main_class',
    ];

    protected $casts = [
        'beta' => 'bool',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_downloads', 'version_id', 'user_id');
    }
}
