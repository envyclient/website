<?php

namespace App\Http\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Auth;
use Livewire\Component;

class Verify extends Component
{
    public function render()
    {
        return view('livewire.auth.verify')->extends('layouts.guest');
    }

    public function resend()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            redirect(RouteServiceProvider::HOME);
        }

        Auth::user()->sendEmailVerificationNotification();

        $this->emit('resent');

        session()->flash('resent');
    }
}
