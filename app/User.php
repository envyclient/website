<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int role_id
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'hwid'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin(): bool
    {
        return $this->role_id == Role::ADMIN[0];
    }

    public function hasPurchased(): bool
    {
        return $this->role_id == Role::PREMIUM[0] || $this->isAdmin();
    }

    public function invoice()
    {
        return $this->hasOne('App\Invoice');
    }
}
