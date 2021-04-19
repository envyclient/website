<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Overtrue\LaravelFavorite\Traits\Favoriter;

/**
 * @property-read int id
 *
 * @property string name
 * @property string email
 * @property Carbon|null email_verified_at
 * @property string password
 * @property string api_token
 * @property string|null hwid
 * @property bool admin
 * @property bool banned
 * @property bool disabled
 * @property string cape
 * @property string image
 * @property string|null current_account
 * @property int|null referral_code_id
 * @property Carbon|null referral_code_used_at
 * @property string|null discord_id
 * @property string|null discord_name
 * @property string|null stripe_id
 *
 * @property-read Carbon created_at
 * @property-read Carbon updated_at
 *
 * @property-read Collection configs
 * @property-read Subscription|null subscription
 * @property-read Plan|null plan
 * @property-read BillingAgreement|null billingAgreement
 * @property-read Collection downloads
 * @property-read ReferralCode|null referralCode
 * @property-read Collection invoices
 * @property-read Collection licenseRequest
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
        'stripe_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'admin',
    ];

    protected $casts = [
        'banned' => 'bool',
    ];

    protected $dates = [
        'email_verified_at',
        'referral_code_used_at',
    ];

    public function scopeSearch(Builder $query, string $search)
    {
        if (!empty($search)) {
            return $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('discord_name', 'like', "%$search%");
            });
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

    public function plan()
    {
        return $this->hasOneThrough(Plan::class, Subscription::class, 'user_id', 'id', 'id', 'plan_id');
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

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function licenseRequests()
    {
        return $this->hasMany(LicenseRequest::class);
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
