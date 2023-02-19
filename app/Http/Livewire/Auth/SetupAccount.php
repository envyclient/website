<?php

namespace App\Http\Livewire\Auth;

use App\Traits\ValidationRules;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class SetupAccount extends Component
{
    use ValidationRules;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public function render()
    {
        return view('livewire.auth.setup-account')->extends('layouts.guest');
    }

    public function submit()
    {
        $this->validate([
            'name' => $this->nameRules(),
            'email' => $this->emailRules(),
            'password' => $this->passwordRules(),
        ]);

        $user = auth()->user();

        // updated the user info
        $user->forceFill([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'email_verified_at' => null,
        ])->save();

        // send the user a confirmation email
        $user->sendEmailVerificationNotification();

        return redirect()->intended(route('home'));
    }
}
