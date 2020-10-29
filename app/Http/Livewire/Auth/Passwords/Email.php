<?php

namespace App\Http\Livewire\Auth\Passwords;

use Illuminate\Contracts\Auth\PasswordBroker;
use Livewire\Component;
use Password;

class Email extends Component
{
    public $email;

    protected $rules = [
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
     * @return PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }
}
