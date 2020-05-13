<?php

namespace App;

use Depsimon\Wallet\HasWallet;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Overtrue\LaravelFavorite\Traits\Favoriter;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasWallet, Favoriter;

    protected $fillable = [
        'name', 'email', 'password', 'api_token', 'admin', 'ban_reason', 'last_launch_user', 'last_launch_at', 'disabled'
    ];

    protected $hidden = [
        'password', 'remember_token', 'email_verified_at', 'admin'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_launch_at' => 'datetime'
    ];

    public function scopeName($query, $name)
    {
        if (!is_null($name)) {
            return $query->where('name', 'LIKE', "%$name%");
        }
        return $query;
    }

    public function configs()
    {
        return $this->hasMany('App\Config');
    }

    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }

    public function subscription()
    {
        return $this->hasOne('App\Subscription');
    }

    public function isBanned(): bool
    {
        return $this->ban_reason !== null;
    }

    public function hasSubscription(): bool
    {
        return $this->subscription()->exists();
    }

    public function getConfigLimit(): int
    {
        return $this->subscription->plan->config_limit;
    }

    public function image(): string
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email)));
    }
}
