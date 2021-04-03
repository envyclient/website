<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;

class EditUserModal extends Component
{
    public User $user;

    protected array $rules = [
        'user.name' => 'required|alpha_dash',
        'user.hwid' => 'nullable|string|min:40|max:40',
        'user.banned' => 'required|bool',
    ];

    public function edit(User $user): void
    {
        $this->user = $user;
    }

    public function save(): void
    {
        $this->user->hwid = match ($this->user->hwid) {
            true => Str::random(40),
            default => null
        };

        $this->validate();
        $this->user->save();

        $this->dispatchBrowserEvent('edit-user-modal-close');

        $this->emitUp('users-update');
    }

    public function render()
    {
        return view('livewire.admin.user.edit-user-modal');
    }

}
