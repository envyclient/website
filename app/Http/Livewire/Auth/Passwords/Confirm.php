<?php

namespace App\Http\Livewire\Auth\Passwords;

use Livewire\Component;

class Confirm extends Component
{
    public string $password = '';

    public function render()
    {
        return view('livewire.auth.passwords.confirm')->extends('layouts.guest');
    }

    public function submit()
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('home'));
    }
}
