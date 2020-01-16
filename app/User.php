<?php

namespace App;

use App\Notifications\SubscriptionUpdate;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\HasWallet;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail, Wallet
{
    use Notifiable, HasWallet;

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

    public function isAdmin(): bool
    {
        return $this->role_id == Role::ADMIN[0];
    }

    public function hasPurchased(): bool
    {
        return $this->role_id == Role::PREMIUM[0] || $this->isAdmin();
    }

    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }

    public function renewSubscription()
    {
        if ($this->safePay($item)) {
            $this->pay($item);
        } else {
            $this->role_id = Role::DEFAULT;
            $this->subscription_end = null;
            $this->save();
            $this->notify(new SubscriptionUpdate($this, 'Your subscription has expired. Please renew it you want to continue using the client.'));
        }
    }
}
