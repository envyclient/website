<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Overtrue\LaravelFavorite\Traits\Favoriter;

/**
 * @property string name
 * @property string email
 * @property null|Carbon email_verified_at
 * @property string password
 * @property string api_token
 * @property null|string hwid
 * @property bool admin
 * @property bool banned
 * @property bool disabled
 * @property string cape
 * @property string image
 * @property null|string current_account
 * @property null|int referral_code_id
 * @property null|Carbon referral_code_used_at
 * @property null|string discord_id
 * @property null|string discord_name
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, Favoriter, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'api_token',
        'hwid',
        'admin',
        'banned',
        'disabled',
        'cape',
        'image',
        'current_account',
        'referral_code_id',
        'referral_code_used_at',
        'discord_id',
        'discord_name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'admin',
    ];

    protected $casts = [
        'banned' => 'bool',
        'email_verified_at' => 'datetime',
        'referral_code_used_at' => 'datetime',
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
        return $this->hasMany(Config::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function billingAgreement()
    {
        return $this->hasOne(BillingAgreement::class);
    }

    public function downloads()
    {
        return $this->belongsToMany(Version::class, 'user_downloads', 'user_id', 'version_id');
    }

    public function referralCode()
    {
        return $this->belongsTo(ReferralCode::class);
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

    public function isBillingAgreementCancelled(): bool
    {
        return $this->billingAgreement->state === 'Cancelled';
    }

    public function getConfigLimit(): int
    {
        return $this->subscription->plan->config_limit;
    }

    public function hasBetaAccess(): bool
    {
        return $this->admin || ($this->hasSubscription() && $this->subscription->plan->beta_access);
    }

    public function hasCapesAccess(): bool
    {
        return $this->admin || ($this->hasSubscription() && $this->subscription->plan->capes_access);
    }
}
