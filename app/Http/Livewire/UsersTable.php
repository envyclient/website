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

    public function render()
    {
        $user = User::with(['subscription'])
            ->name($this->name);

        if ($this->type === 'banned') {
            $user->where('banned', true);
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
