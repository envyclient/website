<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\Subscription;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UsersTable extends Component
{
    use WithPagination;

    protected $listeners = ['users-update' => '$refresh'];

    // filters
    public string $search = '';
    public string $type = 'all';
    public string $subscription = 'ignore';
    public string $referralCode = 'ignore';

    public function render()
    {
        $user = User::query()
            ->with(['subscription.plan', 'referralCode:id,code', 'downloads:id,name'])
            ->search($this->search);

        switch ($this->type) {
            case 'subscribed':
            {
                $user->has('subscription');
                break;
            }
            case 'active-subscription':
            {
                $user->whereRelation('subscription', 'status', Subscription::ACTIVE);
                break;
            }
            case 'cancelled-subscription':
            {
                $user->whereRelation('subscription', 'status', Subscription::CANCELED);
                break;
            }
            case 'banned':
            {
                $user->where('banned', true);
                break;
            }
            case 'using-client':
            {
                $user->where('current_account', '<>', null);
                break;
            }
        }

        if ($this->subscription !== 'ignore') {
            $user->whereRelation('subscription', 'plan_id', $this->subscription);
        }

        if ($this->referralCode !== 'ignore') {
            $user->where('referral_code_id', $this->referralCode);
        }

        return view('livewire.admin.user.users-table', [
            'users' => $user->orderBy('id')->paginate(20),
        ])->extends('layouts.dash');
    }

    public function updateFreeSubscription(User $user, bool $remove): void
    {
        if ($remove) {
            $user->subscription()
                ->where('plan_id', 1)
                ->delete();
        } else {
            $user->subscription()->create([
                'plan_id' => 1,
                'status' => Subscription::ACTIVE,
                'end_date' => now()->addMonth(),
            ]);
        }
    }

    public function updateUserBan(User $user, bool $state): void
    {
        $user->update([
            'banned' => $state,
        ]);
    }

    public function resetUserHWID(User $user): void
    {
        $user->update([
            'hwid' => null,
        ]);
    }
}
