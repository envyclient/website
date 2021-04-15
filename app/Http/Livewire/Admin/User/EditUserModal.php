<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;

class EditUserModal extends Component
{
    public bool $edit;
    public User $user;

    protected array $rules = [
        'user.name' => 'required|alpha_dash',
        'user.hwid' => 'nullable|string|min:40|max:40',
        'user.banned' => 'required|bool',
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
        // update hwid to use strings
        $this->user->hwid = match ($this->user->hwid) {
            true => Str::random(40),
            default => null
        };

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
