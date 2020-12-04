<?php

namespace App\Http\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Auth;
use Livewire\Component;

class Login extends Component
{
    public $email;
    public $password;
    public $remember = false;

    protected $rules = [
        'email' => 'required|string|email',
        'password' => 'required|string',
    ];

    public function render()
    {
        return view('livewire.auth.login')->extends('layouts.guest');
    }

    public function login()
    {
        $this->validate();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', trans('auth.failed'));
            return;
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
