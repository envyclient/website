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
        $user = user::with(['subscription'])
            ->name($this->name);

        if ($this->type === 'banned') {
            $user->where('banned', true);
        }

        return view('livewire.users-table')->with([
            'users' => $user->paginate(20)
        ]);
    }

    public function banUser($id)
    {
        $user = user::findorfail($id);
        if ($user->hassubscription()) {
            $user->subscription->fill([
                'renew' => false
            ])->save();
        }

        $banned = true;
        if ($user->banned) {
            $banned = false;
        }

        $user->fill([
            'banned' => $banned
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
