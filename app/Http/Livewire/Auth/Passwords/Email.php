<?php

namespace App\Http\Livewire\Auth\Passwords;

use Illuminate\Support\Facades\Password;
use Livewire\Component;

class Email extends Component
{
    public string $email = '';

    protected array $rules = [
        'email' => 'required|string|email',
    ];

    public function render()
    {
        return view('livewire.auth.passwords.email')->extends('layouts.guest');
    }

    public function sendResetPasswordLink()
    {
        $this->validate();

        $response = $this->broker()->sendResetLink(['email' => $this->email]);
        if ($response == Password::RESET_LINK_SENT) {
            session()->flash('status', trans($response));
            return;
        }

        $this->addError('email', trans($response));
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }
}
