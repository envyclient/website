<?php

namespace App\Http\Livewire;

use App\Models\Subscription;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UsersTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $name = '';
    public string $type = 'all';
    public string $referralCode = 'ignore';

    public function render()
    {
        $user = User::with(['subscription.plan', 'referralCode'])
            ->name($this->name);

        switch ($this->type) {
            case 'banned':
            {
                $user->where('banned', true);
                break;
            }
            case 'active':
            {
                $user->where('current_account', '<>', null);
                break;
            }
        }

        if ($this->referralCode !== 'ignore') {
            $user->where('referral_code_id', $this->referralCode);
        }

        return view('livewire.users-table')->with([
            'users' => $user->paginate(10),
        ]);
    }

    public function resetUserHWID(int $id): void
    {
        $user = User::findOrFail($id);
        $user->update([
            'hwid' => null,
        ]);

        $this->resetPage();
    }

    public function banUser(int $id): void
    {
        $user = User::findOrFail($id);
        $user->update([
            'banned' => !$user->banned,
        ]);

        $this->resetPage();
    }

    public function freePlan(int $id, bool $removing = false): void
    {
        $user = User::findOrFail($id);

        // removing the users subscription to the free plan
        if ($removing) {
            Subscription::where('user_id', $id)
                ->where('plan_id', 1)
                ->delete();
        }

        $user->update([
            'access_free_plan' => !$user->access_free_plan,
        ]);
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
