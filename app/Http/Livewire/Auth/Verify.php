<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;

class Verify extends Component
{
    public function render()
    {
        return view('livewire.auth.verify')->extends('layouts.guest');
    }

    public function submit()
    {
        $user = auth()->user();

        if ($user->hasVerifiedEmail()) {
            redirect(route('home'));
        }

        $user->sendEmailVerificationNotification();

        $this->emit('resent');

        session()->flash('resent');
    }
}
