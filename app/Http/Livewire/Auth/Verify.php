<?php

namespace App\Http\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Livewire\Component;

class Verify extends Component
{
    public function render()
    {
        return view('livewire.auth.verify')->extends('layouts.guest');
    }

    public function resend()
    {
        $user = auth()->user();
        if ($user->hasVerifiedEmail()) {
            redirect(RouteServiceProvider::HOME);
        }

        $user->sendEmailVerificationNotification();

        $this->emit('resent');

        session()->flash('resent');
    }
}
