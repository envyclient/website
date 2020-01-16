<?php

namespace App;

use App\Notifications\SubscriptionUpdate;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'hwid', 'role_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'subscription_end' => 'date'
    ];

    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }

    public function subscription()
    {
        return $this->hasOne('App\Subscription');
    }

    public function isAdmin(): bool
    {
        return $this->role_id == Role::ADMIN[0];
    }

    public function hasPurchased(): bool
    {
        return $this->role_id == Role::PREMIUM[0] || $this->isAdmin();
    }

    public function renewSubscription()
    {
        $item = $this->subscription->plan;
        if ($this->safePay($item)) {
            $this->pay($item);

            $this->notify(new SubscriptionUpdate($this, 'Your subscription has been renewed.'));
        } else {
            $this->role_id = Role::DEFAULT;
            $this->subscription->end_date = null;
            $this->save();
            $this->notify(new SubscriptionUpdate($this, 'Your subscription has expired. Please renew it you wish to continue using the client.'));
        }
    }
}
