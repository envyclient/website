<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\User;
use Livewire\Component;

class EditUserModal extends Component
{
    public bool $edit;

    public User $user;

    protected array $rules = [
        'user.name' => ['required', 'string', 'min:3', 'max:255', 'alpha_dash'],
    ];

    // make empty user
    public function mount()
    {
        $this->user = User::make();
    }

    // open the edit user modal
    public function edit(User $user): void
    {
        $this->edit = true;
        $this->user = $user;
    }

    public function save(): void
    {
        // validate the user & save
        $this->validate();
        $this->user->save();

        // disable edit mode
        $this->edit = false;

        // update the users table
        $this->emitUp('users-update');
    }

    public function render()
    {
        return view('livewire.admin.user.edit-user-modal');
    }
}
