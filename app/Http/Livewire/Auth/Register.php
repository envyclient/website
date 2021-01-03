<?php

namespace App\Http\Livewire\Auth;

use App\Models\ReferralCode;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public $name;
    public $email;
    public $password;
    public $passwordConfirmation;

    protected $rules = [
        'name' => [
            'required',
            'string',
            'min:3',
            'max:255',
            'alpha_dash',
        ],
        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            'unique:users',
        ],
        'password' => [
            'required',
            'string',
            'min:8',
            'same:passwordConfirmation',
        ],
    ];

    public function render()
    {
        return view('livewire.auth.register')->extends('layouts.guest');
    }

    public function register()
    {
        $this->validate();

        $user = User::create([
            'email' => $this->email,
            'name' => $this->name,
            'password' => Hash::make($this->password),
        ]);

        event(new Registered($user));

        Auth::login($user, true);

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
