<?php

namespace App\Http\Livewire;

use App\User;
use Livewire\Component;
use Livewire\WithPagination;

class UsersTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name = '';
    public $type = 'all';

    public function render()
    {
        $user = User::with(['subscription'])
            ->name($this->name);

        if ($this->type === 'banned') {
            $user->where('banned', true);
        }

        return view('livewire.users-table')->with([
            'users' => $user->paginate(20),
        ]);
    }

    public function resetUserHWID($id)
    {
        $user = User::findOrFail($id);
        $user->fill([
            'hwid' => null
        ])->save();

        $this->resetPage();
    }

    public function banUser($id)
    {
        $user = User::findOrFail($id);
        $user->fill([
            'banned' => !$user->banned
        ])->save();

        $this->resetPage();
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
