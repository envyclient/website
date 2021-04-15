<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
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
        $user = User::with(['subscription.plan', 'referralCode:id,code', 'downloads:id,name'])
            ->search($this->search);

        switch ($this->type) {
            case 'subscribed':
            {
                $user->has('subscription');
                break;
            }
            case 'active-subscription':
            {
                $user->whereHas('subscription.billingAgreement', function (Builder $query) {
                    $query->where('state', 'Active');
                });
                break;
            }
            case 'cancelled-subscription':
            {
                $user->whereHas('subscription.billingAgreement', function (Builder $query) {
                    $query->where('state', 'Cancelled');
                });
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
            $user->whereHas('subscription', function ($q) {
                $q->where('plan_id', $this->subscription);
            });
        }

        if ($this->referralCode !== 'ignore') {
            $user->where('referral_code_id', $this->referralCode);
        }

        return view('livewire.admin.user.users-table', [
            'users' => $user->orderBy('id')->paginate(20),
        ])->extends('layouts.dash');
    }

    public function freeSubscription(int $id, bool $removing = false): void
    {
        $user = User::findOrFail($id);

        // remove free plan from user
        if ($removing) {
            Subscription::where('user_id', $id)
                ->where('plan_id', 1)
                ->delete();
        } else { // give the user free plan
            Subscription::create([
                'user_id' => $user->id,
                'plan_id' => 1,
                'end_date' => now()->addMonth(),
            ]);
        }
    }
}
