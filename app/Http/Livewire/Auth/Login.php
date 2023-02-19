<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;

class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function render()
    {
        return view('livewire.auth.login')->extends('layouts.guest');
    }

    public function submit()
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! auth()->attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', trans('auth.failed'));

            return;
        }

        return redirect()->intended(route('home'));
    }
}
