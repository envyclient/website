<?php

namespace App;

use App\Notifications\SubscriptionUpdate;
use App\Traits\HasWallet;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasWallet;

    protected $fillable = [
        'name', 'email', 'password', 'hwid', 'role_id'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }

    public function subscription()
    {
        return $this->hasOne('App\Subscription');
    }

    public function renewSubscription()
    {
        $user = auth()->user();
        $price = $this->subscription->plan->price;

        if ($user->canWithdraw($price)) {
            $user->withdraw($price);
            $this->notify(new SubscriptionUpdate($this, 'Your subscription has been renewed.'));
        } else {
            $this->role_id = Role::DEFAULT;
            $this->subscription->end_date = null;
            $this->save();
            $this->notify(new SubscriptionUpdate($this, 'Your subscription has expired. Please renew it you wish to continue using the client.'));
        }
    }
}
