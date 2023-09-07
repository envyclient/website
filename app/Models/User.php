<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
 * @property-read int id
 * @property string name
 * @property string email
 * @property Carbon|null email_verified_at
 * @property string password
 * @property string api_token
 * @property string|null hwid
 * @property bool admin
 * @property bool banned
 * @property string cape
 * @property string|null current_account
 * @property int|null referral_code_id
 * @property Carbon|null referral_code_used_at
 * @property-read string image
 * @property-read string hash
 * @property-read string|null remember_token
 * @property-read Carbon created_at
 * @property-read Carbon updated_at
 * @property-read Collection configs
 * @property-read Subscription|null subscription
 * @property-read Collection downloads
 * @property-read ReferralCode|null referralCode
 * @property-read Collection invoices
 * @property-read Collection licenseRequest
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Favoriter;
    use HasFactory;
    use Notifiable;
    use Prunable;

    const CAPE_DEFAULT = 'https://pub-851377c311484efe8f41e8b5018ce8c4.r2.dev/capes/default.png';

    const CAPE_TEMPLATE = 'https://pub-851377c311484efe8f41e8b5018ce8c4.r2.dev/capes/template.png';

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
            $user->api_token = bin2hex(random_bytes(30));

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
        return static::whereNull('email_verified_at')->where('created_at', '<=', now()->subDays(10));
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        });
    }

    protected function cape(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $value ?: User::CAPE_DEFAULT,
        );
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => 'https://avatar.vercel.sh/'.$this->hash.'.svg?text='.strtoupper(substr($attributes['name'], 0, 2)),
        );
    }

    protected function hash(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => md5($attributes['created_at']),
        );
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
