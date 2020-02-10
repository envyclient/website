<?php

namespace App;

use App\Notifications\Generic;
use App\Traits\HasWallet;
use App\Util\AAL;
use Carbon\Carbon;
use Cog\Contracts\Ban\Bannable as BannableContract;
use Cog\Laravel\Ban\Traits\Bannable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Overtrue\LaravelFollow\Traits\CanBeFollowed;
use Overtrue\LaravelFollow\Traits\CanFavorite;
use Overtrue\LaravelFollow\Traits\CanFollow;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject, BannableContract
{
    use Notifiable, HasWallet, CanFollow, CanFavorite, CanBeFollowed, Bannable;

    const CAPES_DIRECTORY = 'public/capes';

    protected $fillable = [
        'name', 'email', 'password', 'hwid', 'admin', 'cape'
    ];

    protected $hidden = [
        'password', 'remember_token', 'email_verified_at', 'aal_name', 'admin'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'client_settings' => 'json'
    ];

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

    public function hasSubscription(): bool
    {
        return $this->subscription()->exists();
    }

    public function renewSubscription()
    {
        $user = auth()->user();
        $plan = $this->subscription->plan;

        if ($user->canWithdraw($plan->price) && $this->subscription->renew) {
            $user->subscription->end_date = Carbon::now()->addDays($this->subscription->plan->interval);
            $user->subscription()->save();

            $user->withdraw($plan->price, 'withdraw', ['plan_id' => $plan->id, 'description' => "Renewal of plan {$plan->title}."]);
            $this->notify(new Generic($this, 'Your subscription has been renewed.'));
        } else {
            $code = AAL::removeUser($this);
            if ($code !== 200 && $code !== 404) {
                return;
            }

            $user->subscription()->delete();
            $this->notify(new Generic($this, 'Your subscription has failed to renew due to lack of credits. Please renew it you wish to continue using the client.'));
        }
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getConfigLimit()
    {
        if ($this->hasSubscription() && $this->subscription->plan->name === 'Lifetime') {
            return 15;
        }
        return 5;
    }
}
