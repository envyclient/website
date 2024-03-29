<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use App\Traits\ValidationRules;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    use ValidationRules;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public function render()
    {
        return view('livewire.auth.register')->extends('layouts.guest');
    }

    public function submit()
    {
        $this->validate([
            'name' => $this->nameRules(),
            'email' => $this->emailRules(),
            'password' => $this->passwordRules(),
        ]);

        $user = User::create([
            'email' => $this->email,
            'name' => $this->name,
            'password' => Hash::make($this->password),
        ]);

        event(new Registered($user));

        auth()->login($user, true);

        return redirect()->intended(route('home'));
    }
}
