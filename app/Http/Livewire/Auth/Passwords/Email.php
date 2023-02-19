<?php

namespace App\Http\Livewire\Auth\Passwords;

use Illuminate\Support\Facades\Password;
use Livewire\Component;

class Email extends Component
{
    public string $email = '';

    public function render()
    {
        return view('livewire.auth.passwords.email')->extends('layouts.guest');
    }

    public function submit()
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $response = Password::broker()->sendResetLink(['email' => $this->email]);

        if ($response == Password::RESET_LINK_SENT) {
            session()->flash('status', trans($response));

            return;
        }

        $this->addError('email', trans($response));
    }
}
