<?php

namespace App;

use App\Notifications\Generic;
use App\Traits\HasWallet;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// TODO: remove user follow package and add new one
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasWallet;

    protected $fillable = [
        'name', 'email', 'password', 'api_token', 'admin', 'ban_reason', 'hwid'
    ];

    protected $hidden = [
        'password', 'remember_token', 'email_verified_at', 'admin', 'ban_reason', 'hwid'
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

    public function isBanned()
    {
        return $this->ban_reason !== null;
    }

    public function hasSubscription(): bool
    {
        return $this->subscription()->exists();
    }

    public function isLifetime()
    {
        return $this->hasSubscription() && $this->subscription->plan->name === 'Lifetime';
    }

    public function getConfigLimit()
    {
        if ($this->isLifetime()) {
            return 15;
        }
        return 5;
    }

    public function renewSubscription()
    {
        $user = auth()->user();
        $plan = $this->subscription->plan;

        if ($user->canWithdraw($plan->price) && $this->subscription->renew) {
            $user->subscription->end_date = Carbon::now()->addDays($this->subscription->plan->interval);
            $user->subscription()->save();

            $user->withdraw($plan->price, 'withdraw', ['plan_id' => $plan->id, 'description' => "Renewal of plan {$plan->title}."]);
            $this->notify(new Generic($this, 'Your subscription has been renewed.', 'Subscription'));
        } else {
            $user->subscription()->delete();
            $this->notify(new Generic($this, 'Your subscription has failed to renew due to lack of credits. Please renew it you wish to continue using the client.', 'Subscription'));
        }
    }
}
