<?php

namespace App\Http\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class SetupAccount extends Component
{
    public string $name = '';
    public string $password = '';
    public string $passwordConfirmation = '';

    protected array $rules = [
        'name' => [
            'required',
            'string',
            'min:3',
            'max:255',
            'alpha_dash',
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
        return view('livewire.auth.setup-account')->extends('layouts.guest');
    }

    public function submit()
    {
        $this->validate();

        auth()->user()->forceFill([
            'name' => $this->name,
            'password' => Hash::make($this->password)
        ])->save();

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
