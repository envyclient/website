<?php

namespace App;

use App\Notifications\SubscriptionUpdate;
use App\Traits\HasWallet;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasWallet;

    protected $fillable = [
        'name', 'email', 'password', 'hwid', 'admin'
    ];

    protected $hidden = [
        'password', 'remember_token'
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
            $this->notify(new SubscriptionUpdate($this, 'Your subscription has been renewed.'));
        } else {
            $this->subscription->end_date = null;
            $this->save();
            $this->notify(new SubscriptionUpdate($this, 'Your subscription has failed to renew due to lack of credits. Please renew it you wish to continue using the client.'));
        }
    }
}
