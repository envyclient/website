<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class ChangePassword extends Component
{
    public $current_password;
    public $password;
    public $password_confirmation;

    protected $rules = [
        'current_password' => 'required',
        'password' => 'required|min:8|confirmed|different:current_password',
        'password_confirmation' => 'required_with:password'
    ];

    public function render()
    {
        return view('livewire.change-password', [
            'user' => auth()->user(),
        ]);
    }

    public function submit()
    {
        $this->validate();

        $user = auth()->user();
        if (!Hash::check($this->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The provided password does not match your current password.'],
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($this->password)
        ])->save();

        session()->flash('message', 'Your password has been updated.');
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->current_password = '';
        $this->password = '';
        $this->password_confirmation = '';
    }
}
