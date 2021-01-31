<?php

namespace App\Http\Livewire;

use App\Models\Subscription;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UsersTable extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $name = '';
    public string $type = 'all';
    public string $subscription = 'ignore';
    public string $referralCode = 'ignore';

    public bool $editMode;
    public User $editing;

    protected array $rules = [
        'editing.name' => 'required|alpha_dash',
        'editing.hwid' => 'nullable|string|min:40|max:40',
        'editing.banned' => 'required|bool',
    ];

    public function render()
    {
        $user = User::with(['subscription.plan', 'referralCode'])
            ->name($this->name);

        switch ($this->type) {
            case 'subscribed':
            {
                $user->has('subscription');
                break;
            }
            case 'active-subscription':
            {
                $user->whereHas('subscription.billingAgreement', function ($q) {
                    $q->where('state', 'Active');
                });
                break;
            }
            case 'cancelled-subscription':
            {
                $user->whereHas('subscription.billingAgreement', function ($q) {
                    $q->where('state', 'Cancelled');
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

        return view('livewire.users-table')->with([
            'users' => $user->orderBy('id')->paginate(10),
        ]);
    }

    public function editMode(User $user): void
    {
        $this->editMode = true;
        $this->editing = $user;
    }

    public function save(): void
    {
        if ($this->editing->hwid === false) {
            $this->editing->hwid = null;
        }
        $this->validate();
        $this->editing->save();
        $this->editMode = false;
    }

    public function freeSubscription(int $id, bool $removing = false): void
    {
        $user = User::findOrFail($id);

        // removing the users subscription to the free plan
        if ($removing) {
            Subscription::where('user_id', $id)
                ->where('plan_id', 1)
                ->delete();
        } else {
            // give the user free plan
            Subscription::create([
                'user_id' => $user->id,
                'plan_id' => 1,
                'end_date' => now()->addMonth(),
            ]);
        }
    }

    public function updatingName()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }
}
