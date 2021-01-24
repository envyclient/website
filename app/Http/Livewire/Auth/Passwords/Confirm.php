<?php

namespace App\Http\Livewire\Auth\Passwords;

use App\Providers\RouteServiceProvider;
use Livewire\Component;

class Confirm extends Component
{
    public string $password = '';

    protected $rules = [
        'password' => 'required|string|password',
    ];

    public function render()
    {
        return view('livewire.auth.passwords.confirm')->extends('layouts.guest');
    }

    public function confirm()
    {
        $this->validate();

        session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
