<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;
use Overtrue\LaravelFavorite\Traits\Favoriter;

/**
 * @property-read integer id
 *
 * @property string name
 * @property string email
 * @property Carbon|null email_verified_at
 * @property string password
 * @property string api_token
 * @property string|null hwid
 * @property boolean admin
 * @property boolean banned
 * @property string cape
 * @property string|null current_account
 * @property integer|null referral_code_id
 * @property Carbon|null referral_code_used_at
 * @property string|null discord_id
 * @property string|null discord_name // TODO: remove
 *
 * @property-read string image
 * @property-read string|null remember_token
 * @property-read Carbon created_at
 * @property-read Carbon updated_at
 *
 * @property-read Collection configs
 * @property-read Subscription|null subscription
 * @property-read Plan|null plan
 * @property-read Collection downloads
 * @property-read ReferralCode|null referralCode
 * @property-read Collection invoices
 * @property-read Collection licenseRequest
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use Favoriter;
    use HasFactory;
    use Prunable;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'api_token',
        'hwid',
        'admin',
        'banned',
        'cape',
        'current_account',
        'referral_code_id',
        'referral_code_used_at',
        'discord_id',
        'discord_name',
    ];

    protected $hidden = [
        'email_verified_at',
        'password',
        'admin',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'admin' => 'bool',
        'banned' => 'bool',
        'referral_code_id' => 'bool',
        'referral_code_used_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function (User $user) {

            // generate a random api_token
            $user->api_token = bin2hex(openssl_random_pseudo_bytes(30));

            // set the default cape
            $user->cape = asset('assets/capes/default.png');

            // handle referral code cookie
            if (request()->hasCookie('referral') && ReferralCode::where('code', request()->cookie('referral'))->exists()) {
                $code = ReferralCode::where('code', request()->cookie('referral'))->first();
                $user->referral_code_id = $code->id;
                $user->referral_code_used_at = now();

                // forgetting the referral cookie
                Cookie::queue(Cookie::forget('referral'));
            }
        });
    }

    public function prunable()
    {
        return static::whereNull('email_verified_at')
            ->where('created_at', '<=', now()->subDays(10));
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }
        return $query->where(function (Builder $query) use ($search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('discord_name', 'like', "%$search%");
        });
    }

    public function getImageAttribute(): string
    {
        return 'https://avatar.tobi.sh/avatar/' . md5(strtolower(trim($this->email))) . '.svg?text=' . strtoupper(substr($this->name, 0, 2));
    }

    public function configs(): HasMany
    {
        return $this->hasMany(Config::class);
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class);
    }

    public function downloads(): BelongsToMany
    {
        return $this->belongsToMany(Version::class, 'user_downloads', 'user_id', 'version_id');
    }

    public function referralCode(): BelongsTo
    {
        return $this->belongsTo(ReferralCode::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function licenseRequests(): HasMany
    {
        return $this->hasMany(LicenseRequest::class);
    }

    public function hasSubscription(): bool
    {
        return $this->subscription()->exists();
    }

    public function hasBetaAccess(): bool
    {
        return $this->hasSubscription() && $this->subscription->plan->beta_access;
    }

    public function hasCapesAccess(): bool
    {
        return $this->hasSubscription() && $this->subscription->plan->capes_access;
    }
}
