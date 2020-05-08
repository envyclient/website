<?php

namespace App;

use App\Notifications\Generic;
use App\Traits\HasWallet;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Overtrue\LaravelFavorite\Traits\Favoriter;

// TODO: replace with https://github.com/depsimon/laravel-wallet
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasWallet, Favoriter;

    protected $fillable = [
        'name', 'email', 'password', 'api_token', 'admin', 'ban_reason', 'hwid', 'last_launch_user', 'last_launch_at', 'ban_reason'
    ];

    protected $hidden = [
        'password', 'remember_token', 'email_verified_at', 'admin'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_launch_at' => 'datetime'
    ];

    public function scopeName($query, $name)
    {
        if (!is_null($name)) {
            return $query->where('name', 'LIKE', "%$name%");
        }
        return $query;
    }

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

    public function getConfigLimit()
    {
        return $this->subscription->plan->config_limit;
    }

    public function image(): string
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email)));
    }

    public function renewSubscription(): bool
    {
        $user = auth()->user();
        $plan = $this->subscription->plan;

        if ($user->canWithdraw($plan->price) && $this->subscription->renew) {
            $user->subscription->end_date = Carbon::now()->addDays($this->subscription->plan->interval);
            $user->subscription->save();

            $user->withdraw($plan->price, 'withdraw', ['plan_id' => $plan->id, 'description' => "Renewal of plan {$plan->title}."]);
            $this->notify(new Generic($this, 'Your subscription has been renewed.', 'Subscription'));
            return true;
        } else {
            $user->subscription()->delete();
            $this->notify(new Generic($this, 'Your subscription has failed to renew due to lack of credits. Please renew it you wish to continue using the client.', 'Subscription'));
            return false;
        }
    }
}
