<?php

namespace App;

use App\Notifications\Generic;
use App\Traits\HasWallet;
use App\Util\AAL;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Overtrue\LaravelFollow\Traits\CanBeFollowed;
use Overtrue\LaravelFollow\Traits\CanFavorite;
use Overtrue\LaravelFollow\Traits\CanFollow;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use Notifiable, HasWallet, CanFollow, CanFavorite, CanBeFollowed;

    const CAPES_DIRECTORY = 'public/capes';

    protected $fillable = [
        'name', 'email', 'password', 'hwid', 'admin', 'cape'
    ];

    protected $hidden = [
        'password', 'remember_token', 'email_verified_at', 'aal_name', 'admin'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime'
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
        return $this->subscription()->exists() && $this->subscription->end_date != null;
    }

    public function renewSubscription()
    {
        $user = auth()->user();
        $price = $this->subscription->plan->price;

        if ($user->canWithdraw($price) && $this->subscription->renew) {
            $user->subscription->end_date = Carbon::now()->addDays($this->subscription->plan->interval);
            $user->subscription()->save();

            $user->withdraw($price);
            $this->notify(new Generic($this, 'Your subscription has been renewed.'));
        } else {

            //TODO: check return code
            AAL::removeUser($this);

            $this->subscription->end_date = null;
            $this->save();
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
}
