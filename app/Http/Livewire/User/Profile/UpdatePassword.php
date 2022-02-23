<?php

namespace App\Http\Livewire\User\Profile;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class UpdatePassword extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected array $rules = [
        'current_password' => ['required', 'current_password'],
        'password' => ['required', 'min:8', 'confirmed', 'different:current_password'],
        'password_confirmation' => ['required_with:password'],
    ];

    public function render()
    {
        return view('livewire.user.profile.update-password');
    }

    public function submit()
    {
        $this->validate();

        auth()->user()->forceFill([
            'password' => Hash::make($this->password)
        ])->save();

        $this->smallNotify('Your password has been updated.');
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->current_password = '';
        $this->password = '';
        $this->password_confirmation = '';
    }
}
