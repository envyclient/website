<?php

namespace App\Http\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class SetupAccount extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $passwordConfirmation = '';

    protected array $rules = [
        'name' => ['required', 'string', 'min:3', 'max:255', 'alpha_dash', 'unique:users'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'same:passwordConfirmation'],
    ];

    public function render()
    {
        return view('livewire.auth.setup-account')->extends('layouts.guest');
    }

    public function submit()
    {
        $this->validate();

        $user = auth()->user();

        // updated the user info
        $user->forceFill([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'email_verified_at' => null,
        ])->save();

        // send the user an confirmation email
        $user->sendEmailVerificationNotification();

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
