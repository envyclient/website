<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Overtrue\LaravelFavorite\Traits\Favoriter;

/**
 * @property string name
 * @property string email
 * @property string email_verified_at
 * @property string password
 * @property string api_token
 * @property string hwid
 * @property bool admin
 * @property bool banned
 * @property bool disabled
 * @property bool access_free_plan
 * @property string gravatar_url
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, Favoriter;

    protected $fillable = [
        'name',
        'email',
        'password',
        'api_token',
        'hwid',
        'admin',
        'banned',
        'disabled',
        'access_free_plan'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'admin'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime'
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
        return $this->hasMany('App\Models\Config');
    }

    public function subscription()
    {
        return $this->hasOne('App\Models\Subscription');
    }

    public function billingAgreement()
    {
        return $this->hasOne('App\Models\BillingAgreement');
    }

    public function downloads()
    {
        return $this->belongsToMany('App\Models\Version', 'user_downloads', 'user_id', 'version_id');
    }

    public function gameSessions()
    {
        return $this->hasMany('App\Models\GameSession');
    }

    public function hasSubscription(): bool
    {
        return $this->subscription()->exists();
    }

    public function subscribedToFreePlan(): bool
    {
        return $this->subscription()->exists() && $this->subscription->plan_id === 1;
    }

    public function hasBillingAgreement(): bool
    {
        return $this->billingAgreement()->exists();
    }

    public function getConfigLimit(): int
    {
        return $this->subscription->plan->config_limit;
    }

    public function hasBetaAccess(): bool
    {
        return $this->admin || ($this->hasSubscription() && $this->subscription->plan->beta_access);
    }

    public function getGravatarUrlAttribute(): string
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email)));
    }
}
